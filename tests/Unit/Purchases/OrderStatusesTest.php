<?php


namespace Tests\Unit\Purchases;


use App\Purchases\Order;
use App\Purchases\OrderedKit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function marking_an_orders_single_ordered_kit_as_done_completes_an_order()
    {
        $order = factory(Order::class)->state('paid')->create();
        $ordered_kit = factory(OrderedKit::class)->state('due')->create([
            'order_id' => $order->id,
        ]);

        $ordered_kit->packedAndDelivered();

        $this->assertSame(Order::STATUS_COMPLETE, $order->fresh()->status);
    }

    /**
     *@test
     */
    public function marking_one_kit_as_done_does_not_complete_an_order_if_still_has_kits()
    {
        $order = factory(Order::class)->state('paid')->create();
        $ordered_kitA = factory(OrderedKit::class)->state('due')->create([
            'order_id' => $order->id,
        ]);
        $ordered_kitB = factory(OrderedKit::class)->state('due')->create([
            'order_id' => $order->id,
        ]);

        $ordered_kitA->packedAndDelivered();

        $this->assertSame(Order::STATUS_OPEN, $order->fresh()->status);
    }
}
