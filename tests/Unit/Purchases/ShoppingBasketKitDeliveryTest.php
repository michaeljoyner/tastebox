<?php

namespace Tests\Unit\Purchases;

use App\DeliveryArea;
use App\Orders\Menu;
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
    }
}
