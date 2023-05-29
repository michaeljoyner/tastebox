<?php

namespace Tests\Unit\Purchases;

use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Kit;
use App\Purchases\ShoppingBasket;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingBasketKitDeliveryTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function adding_a_kit_to_an_anonymous_basket_assigns_blank_address()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();

        $kit = $basket->addKit($menu->id);

        $this->assertSame(DeliveryArea::NOT_SET, $kit->delivery_address->area);
        $this->assertSame('', $kit->delivery_address->address);
        $this->assertNull($kit->deliver_with);
    }

    /**
     *@test
     */
    public function adding_a_kit_to_a_members_basket_assigns_correct_address()
    {
        $member = factory(User::class)->state('member')->create();
        $member->initiateProfile();
        $member->profile->update([
            'address_line_one' => '123 Test street',
            'address_line_two' => 'Testville',
            'address_city' => 'Hilton',
        ]);

        $basket = ShoppingBasket::for($member);
        $menu = factory(Menu::class)->state('upcoming')->create();

        $kit = $basket->addKit($menu->id);

        $this->assertSame(DeliveryArea::HILTON, $kit->delivery_address->area);
        $this->assertSame('123 Test street, Testville', $kit->delivery_address->address);
        $this->assertNull($kit->deliver_with);
    }


    /**
     *@test
     */
    public function adding_a_second_kit_for_a_week_will_be_set_set_be_delivered_with_first_kit()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();

        $test_address = new DeliveryAddress(DeliveryArea::HILTON, '123 test street');

        $kitA = $basket->addKit($menu->id);
        $kitA->setDeliveryAddress($test_address);


        $this->assertSame(DeliveryArea::HILTON, $kitA->delivery_address->area);
        $this->assertSame('123 test street', $kitA->delivery_address->address);
        $this->assertNull($kitA->deliver_with);

        $kitB = $basket->addKit($menu->id);

        $this->assertSame(DeliveryArea::HILTON, $kitB->delivery_address->area);
        $this->assertSame('123 test street', $kitB->delivery_address->address);
        $this->assertSame($kitA->id, $kitB->deliver_with);
    }

    /**
     *@test
     */
    public function updating_address_of_first_kit_of_menu_will_update_any_kit_addresses_to_be_delivered_with()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();

        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);
        $kitC = $basket->addKit($menu->id);

        $kitA->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HILTON, '234 test avenue'));

        $this->assertSame(DeliveryArea::HILTON, $kitB->delivery_address->area);
        $this->assertSame('234 test avenue', $kitB->delivery_address->address);

        $this->assertSame(DeliveryArea::HILTON, $kitC->delivery_address->area);
        $this->assertSame('234 test avenue', $kitC->delivery_address->address);
    }

    /**
     *@test
     */
    public function removing_a_kit_from_basket_will_correctly_adjust_delivered_with_kits()
    {
        //if a kit is the first kit for a menu, subsequent kits will be set to be delivered with that kit.
        // If the original kit is removed, we need to adjust the other kits so there is a new first kit

        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();

        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);
        $kitC = $basket->addKit($menu->id);

        $this->assertSame($kitA->id, $kitB->deliver_with);
        $this->assertSame($kitA->id, $kitC->deliver_with);

        $basket->discardKit($kitA->id);

        $this->assertNull($kitB->deliver_with);
        $this->assertSame($kitB->id, $kitC->deliver_with);



    }

    /**
     *@test
     */
    public function can_set_a_kit_to_be_delivered_with_another_kit_from_same_menu()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();

        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);

        $kitA->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HILTON, 'original address'));
        $kitB->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HOWICK, 'unique address'));

        $kitB->deliverWith($kitA);

        $this->assertSame(DeliveryArea::HILTON, $kitB->delivery_address->area);
        $this->assertSame('original address', $kitB->delivery_address->address);
        $this->assertSame($kitA->id, $kitB->deliver_with);


    }

    /**
     *@test
     */
    public function can_set_a_kit_to_be_delivered_with_another_kit_from_different_menu()
    {
        $basket = ShoppingBasket::for(null);
        $menuA = factory(Menu::class)->state('upcoming')->create();
        $menuB = factory(Menu::class)->state('upcoming')->create();

        $kitA = $basket->addKit($menuA->id);
        $kitB = $basket->addKit($menuB->id);

        $kitA->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HILTON, 'original address'));
        $kitB->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HOWICK, 'unique address'));

        $kitB->deliverWith($kitA);

        $this->assertSame(DeliveryArea::HILTON, $kitB->delivery_address->area);
        $this->assertSame('original address', $kitB->delivery_address->address);
        $this->assertNull($kitB->deliver_with);
    }

    /**
     *@test
     */
    public function adding_a_kit_for_a_second_menu_will_use_address_from_previous_kit_if_set()
    {
        $basket = ShoppingBasket::for(null);
        $menuA = factory(Menu::class)->state('upcoming')->create();
        $menuB = factory(Menu::class)->state('upcoming')->create();

        $test_address = new DeliveryAddress(DeliveryArea::HILTON, '123 test street');

        $kitA = $this->createEligibleKit($basket, $menuA);
        $kitA->setDeliveryAddress($test_address);

        $kitB = $this->createEligibleKit($basket, $menuB);


        $this->assertSame(DeliveryArea::HILTON, $kitB->delivery_address->area);
        $this->assertSame('123 test street', $kitB->delivery_address->address);
        $this->assertNull($kitB->deliver_with);


    }

    /**
     *@test
     */
    public function can_set_address_for_all_unset_kits()
    {
        $basket = ShoppingBasket::for(null);
        $menuA = factory(Menu::class)->state('upcoming')->create();
        $menuB = factory(Menu::class)->state('upcoming')->create();

        $test_address = new DeliveryAddress(DeliveryArea::HILTON, '123 test street');

        $kitA = $basket->addKit($menuA->id);
        $kitB = $basket->addKit($menuB->id);
        $kitC = $basket->addKit($menuB->id);

        $basket->setAddressForUnsetKits($test_address);


        $this->assertSame(DeliveryArea::HILTON, $kitA->delivery_address->area);
        $this->assertSame('123 test street', $kitA->delivery_address->address);
        $this->assertNull($kitA->deliver_with);

        $this->assertSame(DeliveryArea::HILTON, $kitB->delivery_address->area);
        $this->assertSame('123 test street', $kitB->delivery_address->address);
        $this->assertNull($kitB->deliver_with);

        $this->assertSame(DeliveryArea::HILTON, $kitC->delivery_address->area);
        $this->assertSame('123 test street', $kitC->delivery_address->address);
        $this->assertSame($kitB->id, $kitC->deliver_with);
    }

    private function createEligibleKit($basket, $menu): Kit
    {
        $kit = $basket->addKit($menu->id);
        $meals = factory(Meal::class, 3)->create();
        $meals->each(fn (Meal $meal) => $kit->setMeal($meal, 2));

        return $kit;
    }
}
