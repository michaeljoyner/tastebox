<?php

namespace Tests\Unit\Memberships;

use App\Orders\Menu;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function can_check_if_order_has_been_placed_for_next_menu()
    {
        $member = factory(User::class)->state('member')->create();

        $menu = factory(Menu::class)->create([
            'current_from' => now()->startOfWeek()->addWeek(),
            'current_to' => now()->startOfWeek()->addWeek()->addDays(5),
            'delivery_from' => now()->startOfWeek()->addWeek()->addDay(),
            'can_order' => true,
        ]);

        $this->assertFalse($member->hasPlacedOrderForNextMenu());

        $order = factory(Order::class)->state('paid')->create(['user_id' => $member->id]);
        factory(OrderedKit::class)->create(['menu_id' => $menu->id, 'order_id' => $order->id]);

        $this->assertTrue($member->hasPlacedOrderForNextMenu());
    }
}
