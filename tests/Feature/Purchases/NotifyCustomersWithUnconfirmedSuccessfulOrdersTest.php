<?php


namespace Tests\Feature\Purchases;


use App\Mail\AwaitingPaymentConfirmation;
use App\Purchases\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyCustomersWithUnconfirmedSuccessfulOrdersTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function customers_without_payfast_confirmation_are_notified_after_three_hours()
    {
        Mail::fake();

        $abandoned = factory(Order::class)->state('created')->create();
        $pending = factory(Order::class)->state('unpaid')->create();
        $complete = factory(Order::class)->state('paid')->create();

        $this->travel(2)->hours();
        Artisan::call('orders:notify-long-pending');

        Mail::assertNothingQueued();

        $this->travel(1)->hours();
        Artisan::call('orders:notify-long-pending');

        Mail::assertQueued(AwaitingPaymentConfirmation::class, function($mail) use ($abandoned, $pending, $complete) {
            $this->assertTrue($pending->is($mail->order));
            $this->assertFalse($abandoned->is($mail->order));
            $this->assertFalse($complete->is($mail->order));
            return true;
        });
    }

    /**
     *@test
     */
    public function customers_are_not_notified_more_than_once()
    {
        Mail::fake();

        $pending = factory(Order::class)->state('unpaid')->create();


        $this->travel(3)->hours();
        Artisan::call('orders:notify-long-pending');

        Mail::assertQueued(AwaitingPaymentConfirmation::class, 1);

        $this->travel(3)->hours();
        Artisan::call('orders:notify-long-pending');

        Mail::assertQueued(AwaitingPaymentConfirmation::class, 1);
    }
}
