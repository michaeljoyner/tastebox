<?php

namespace App\Purchases;

use App\AddOns\AddOn;
use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Orders\Menu;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShoppingBasket
{
    const SESSION = 'session';
    const DB = 'db';


    public function __construct(public Collection $kits, private string $storage_type, private ?User $owner = null)
    {
    }

    public static function for(?User $user): ShoppingBasket
    {
        if (!$user || !$user->isMember()) {
            return new ShoppingBasket(collect(session('basket.kits', [])), self::SESSION);
        }

        if (!$user->shoppingBasket) {
            $user->shoppingBasket()->create(['contents' => serialize([])]);
        }
        $contents = unserialize($user->fresh()->shoppingBasket->contents);

        return new ShoppingBasket(collect($contents), self::DB, $user);

    }

    public function getKit(string $kit_id): ?Kit
    {
        return $this->kits->first(fn(Kit $kit) => $kit->id === $kit_id);
    }

    public function getKitOrFail(string $kit_id): Kit
    {
        $kit = $this->kits->first(fn(Kit $kit) => $kit->id === $kit_id);

        if (!$kit) {
            throw new NotFoundHttpException("No kit exists with that id");
        }

        return $kit;
    }

    private function firstKitForMenu(int $menu_id): ?Kit
    {
        return $this->kits->first(fn(Kit $kit) => $kit->menu_id === $menu_id);
    }

    public function addKit(int $menu_id)
    {
        $address = null;
        $existing_menu_kit = $this->firstKitForMenu($menu_id);
        $existing_kit = $this->kits->first(fn (Kit $kit) => $kit->eligibleForOrder());

        if ($existing_menu_kit) {
            $address = $existing_menu_kit->delivery_address;
        }

        if (!$existing_menu_kit && $existing_kit) {
            $address = $existing_kit->delivery_address;
        }

        $kit = new Kit(
            $menu_id,
            collect([]),
            collect([]),
            $address ?: DeliveryAddress::for($this->owner),
            $this->kits->count()
        );

        if ($existing_menu_kit) {
            $kit->deliver_with = $existing_menu_kit->id;
        }

        $this->kits->push($kit);

        $this->save();

        return $kit;
    }

    public function addMealToKit(string $kit_id, Meal $meal, int $servings)
    {
        $kit = $this->getKit($kit_id);

        if (!$kit) {
            throw new \InvalidArgumentException("no kit with id {$kit_id} in basket");
        }

        $kit->setMeal($meal, $servings);

        $this->setKit($kit);

        $this->save();
    }

    public function removeMealFromKit(string $kit_id, int $meal_id)
    {
        $kit = $this->getKit($kit_id);

        if (!$kit) {
            throw new \InvalidArgumentException("no kit with id {$kit_id} in basket");
        }

        $kit->removeMeal($meal_id);

        $this->setKit($kit);

        $this->save();
    }

    public function addAddOnToKit(string $kit_id, Addon $addon, int $qty)
    {
        $kit = $this->getKit($kit_id);

        if (!$kit) {
            throw new \InvalidArgumentException("no kit with id {$kit_id} in basket");
        }

        $kit->setAddOn($addon, $qty);

        $this->setKit($kit);

        $this->save();
    }

    public function removeAddOnFromKit(string $kit_id, string $add_on_uuid)
    {
        $kit = $this->getKit($kit_id);

        if (!$kit) {
            throw new \InvalidArgumentException("no kit with id {$kit_id} in basket");
        }

        $kit->removeAddOn($add_on_uuid);

        $this->setKit($kit);

        $this->save();
    }


    private function setKit(Kit $kit)
    {
        $this->kits = $this->kits->map(
            fn(Kit $k) => $k->id === $kit->id ? $kit : $k
        );
    }

    public function discardKit(string $kit_id)
    {
        $this->adjustDeliverWithKits($kit_id);

        $this->kits = $this->kits->reject(
            fn(Kit $kit) => $kit->id === $kit_id
        );

        $this->save();
    }

    public function updateSecondaryKitAddresses(string $kit_id, DeliveryAddress $address)
    {
        $this->kits->filter(fn(Kit $kit) => ($kit->deliver_with === $kit_id) && (!$kit->delivery_address->isSameAs($address)))
                   ->each(fn(Kit $kit) => $kit->setDeliveryAddress($address, true));
    }

    public function setAddressForUnsetKits(DeliveryAddress $address)
    {
        $this->kits->filter(fn(Kit $kit) => $kit->requiresAddress())
                   ->each(function(Kit $kit) use ($address) {
                       $original = $this->primaryKitForAddress($address, $kit->menu_id);

                       $original ? $kit->deliverWith($original) : $kit->setDeliveryAddress($address);
                   } );
    }

    private function primaryKitForAddress(DeliveryAddress $address, int $menu_id): ?Kit
    {
        return $this->kits->first(fn(Kit $kit) => ($kit->menu_id === $menu_id) && $kit->delivery_address->isSameAs($address));
    }

    private function adjustDeliverWithKits(string $kit_id)
    {
        $deliver_with = $this->kits->filter(fn(Kit $kit) => $kit->deliver_with === $kit_id);

        if (!$deliver_with->count()) {
            return;
        }
        $new_lead = $deliver_with->first();
        $deliver_with->each(fn(Kit $kit) => $kit->deliver_with = $new_lead->id);

        $new_lead->deliver_with = null;
    }

    public function missingDeliveryAddresses(): bool
    {
        return $this->kits
            ->filter(fn (Kit $kit) => $kit->eligibleForOrder())
            ->contains(fn (Kit $kit) => $kit->requiresAddress());
    }

    public function hasKit(string $kit_id): bool
    {
        return $this->kits->contains(fn(Kit $kit) => $kit->id === $kit_id);
    }

    public function getKitName(?string $kit_id): string
    {
        return $this->kits->first(fn(Kit $kit) => $kit->id === $kit_id)?->name ?? '';
    }


    public function getMenuForKit(string $kit_id): Menu
    {
        $kit = $this->kits->first(fn($k) => $k->id === $kit_id);

        return Menu::find($kit->menu_id ?? null);
    }

    private function save()
    {
        if ($this->storage_type === self::SESSION) {
            session(['basket.kits' => $this->kits->all()]);
        }

        if ($this->storage_type === self::DB) {
            $this->owner->fresh()->shoppingBasket->update(['contents' => serialize($this->kits->all())]);
        }
    }

    public function price(): float
    {
        return $this
            ->kits
            ->filter(fn(Kit $kit) => $kit->eligibleForOrder())
            ->sum(fn(Kit $kit) => $kit->price());
    }

    public function presentForReview()
    {
        $kits = $this
            ->kits
            ->filter->isValid()
                    ->map(fn(Kit $kit) => (new BasketPresenter($this))->presentKit($kit))->values()->all();


        return [
            'total_boxes'              => count($kits),
            'total_price'              => $this->price(),
            'kits'                     => $kits,
            'suggested_addresses'      => $this->kits->filter(fn(Kit $kit
            ) => $kit->delivery_address->area !== DeliveryArea::NOT_SET)
                                                     ->unique(fn(Kit $kit
                                                     ) => $kit->delivery_address->area->name . $kit->delivery_address->address)
                                                     ->map(fn(Kit $kit) => [
                                                         'kit_id'           => $kit->id,
                                                         'delivery_area'    => [
                                                             'key'   => $kit->delivery_address->area->value,
                                                             'value' => $kit->delivery_address->area->description(),
                                                         ],
                                                         'delivery_address' => $kit->delivery_address->address,
                                                     ])->values()->all(),
            'available_delivery_areas' => DeliveryArea::activeAreas(),
        ];
    }

    public function clear()
    {
        $this->kits = collect([]);
        $this->save();
    }

    public function isEmpty()
    {
        return $this->kits->count() === 0;
    }

    public function restoreFromOrder(Order $order)
    {
        $this->clear();

        $order->orderedKits->each(function(OrderedKit $orderedKit) {
            $kit = $this->addKit($orderedKit->menu_id);
            $orderedKit->meals
                ->each(
                    fn (Meal $m) => $this->addMealToKit($kit->id, $m, $m->pivot->servings)
                );
            $kit->delivery_address = $orderedKit->deliveryAddress();

            if(!$kit->eligibleForOrder()) {
                $this->discardKit($kit->id);
            }

            $this->save();
        });
    }


}
