<?php

namespace Tests\Unit\Purchases;

use App\DeliveryAddress;
use App\DeliveryArea;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateDeliveryAddressForKitTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_the_delivery_address_for_a_kit_with_a_new_address()
    {
        $this->withoutExceptionHandling();

        $cart = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('current')->create();
        $kit = $cart->addKit($menu->id);

        $response = $this->asGuest()->postJson("/api/kits/{$kit->id}/delivery-address", [
            "type" => "address",
            "delivery_area" => DeliveryArea::HILTON->value,
            "delivery_address" => "1234 test street",
            'apply_to_all_unset' => false,
        ]);
        $response->assertSuccessful();

        $this->assertSame(DeliveryArea::HILTON, $kit->delivery_address->area);
        $this->assertSame('1234 test street', $kit->delivery_address->address);
        $this->assertNull($kit->deliver_with);
    }

    /**
     *@test
     */
    public function update_the_delivery_address_to_be_same_as_existing_kit()
    {
        $this->withoutExceptionHandling();

        $cart = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('current')->create();
        $kitA = $cart->addKit($menu->id);
        $kitB = $cart->addKit($menu->id);

        $test_address = new DeliveryAddress(DeliveryArea::HOWICK, '234 shady street');

        $kitA->setDeliveryAddress($test_address);

        $response = $this->asGuest()->postJson("/api/kits/{$kitB->id}/delivery-address", [
            "type" => "kit",
            "kit_id" => $kitA->id,
            'apply_to_all_unset' => false,
        ]);
        $response->assertSuccessful();

        $this->assertSame($test_address->area, $kitB->delivery_address->area);
        $this->assertSame($test_address->address, $kitB->delivery_address->address);
        $this->assertSame($kitA->id, $kitB->deliver_with);
    }

    /**
     *@test
     */
    public function update_all_unset_kits_with_same_address()
    {
        $this->withoutExceptionHandling();

        $cart = ShoppingBasket::for(null);
        $menuA = factory(Menu::class)->state('current')->create();
        $menuB = factory(Menu::class)->state('current')->create();
        $kitA = $cart->addKit($menuA->id);
        $kitB = $cart->addKit($menuB->id);


        $response = $this->asGuest()->postJson("/api/kits/{$kitA->id}/delivery-address", [
            "type" => "address",
            "delivery_area" => DeliveryArea::HOWICK->value,
            "delivery_address" => "123 test street",
            "apply_to_all_unset" => true,
        ]);
        $response->assertSuccessful();

        $this->assertSame(DeliveryArea::HOWICK, $kitA->delivery_address->area);
        $this->assertSame("123 test street", $kitA->delivery_address->address);
        $this->assertNull($kitA->deliver_with);

        $this->assertSame(DeliveryArea::HOWICK, $kitB->delivery_address->area);
        $this->assertSame("123 test street", $kitB->delivery_address->address);
        $this->assertNull($kitB->deliver_with);
    }

    /**
     *@test
     */
    public function the_type_is_required_as_either_address_or_kit()
    {
        $this->assertFieldIsInvalid([
            'type' => null,
            'kit_id' => Str::uuid()->toString(),
            'apply_to_all_unset' => false,
        ]);

        $this->assertFieldIsInvalid([
            'type' => 'neither_kit_nor_address',
            'kit_id' => Str::uuid()->toString(),
            'apply_to_all_unset' => false,
        ]);
    }

    /**
     *@test
     */
    public function the_delivery_area_is_required_when_type_is_address()
    {
        $this->assertFieldIsInvalid([
            'delivery_area' => null,
            'type' => 'address',
            'delivery_address' => '123 test street',
            'apply_to_all_unset' => false,
        ]);
    }

    /**
     *@test
     */
    public function the_delivery_area_must_be_a_value_of_DeliveryArea()
    {
        $this->assertFieldIsInvalid([
            'delivery_area' => 'not-a-DeliveryArea-enum-value',
            'type' => 'address',
            'delivery_address' => '123 test street',
            'apply_to_all_unset' => false,
        ]);
    }

    /**
     *@test
     */
    public function the_delivery_address_is_required_if_type_is_address()
    {
        $this->assertFieldIsInvalid([
            'delivery_address' => '',
            'delivery_area' => DeliveryArea::HILTON->value,
            'type' => 'address',
            'apply_to_all_unset' => false,
        ]);
    }

    /**
     *@test
     */
    public function the_kit_id_is_required_if_type_is_kit()
    {
        $this->assertFieldIsInvalid([
            'kit_id' => '',
            'type' => 'kit',
            'apply_to_all_unset' => false,
        ]);
    }

    /**
     *@test
     */
    public function apply_to_all_unset_must_be_a_boolean()
    {
        $this->assertFieldIsInvalid([
            'apply_to_all_unset' => 'not-a-bool',
            'delivery_address' => '123 test street',
            'delivery_area' => DeliveryArea::HILTON->value,
            'type' => 'address',
        ]);
    }

    private function assertFieldIsInvalid(array $fields)
    {
        $cart = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('current')->create();
        $kit = $cart->addKit($menu->id);

        $response = $this->asGuest()->postJson("/api/kits/{$kit->id}/delivery-address", $fields);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($fields));
    }
}
