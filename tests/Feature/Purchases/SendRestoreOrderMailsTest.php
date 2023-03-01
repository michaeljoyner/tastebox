<?php

namespace Tests\Feature\Purchases;

use App\Mail\RestoreAbandonedOrderMail;
use App\Memberships\MemberProfile;
use App\Purchases\Order;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendRestoreOrderMailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function send_mails_to_recently_abandoned_orders()
    {
        Mail::fake();
        $member = factory(MemberProfile::class)->create();
        factory(Order::class)->state('paid')->create([
            'created_at' => now()->subHour(),
            'email' => 'test@test.test'
        ]);
        $abandoned = factory(Order::class)->state('created')->create([
            'created_at' => now()->subDays(2)
        ]);
        $old_abandoned = factory(Order::class)->state('created')->create([
            'created_at' => now()->subDays(8)
        ]);
        $replaced = factory(Order::class)->state('created')->create([
            'created_at' => now()->subDays(2),
            'email' => 'test@test.test'
        ]);
        $member_abandoned = factory(Order::class)->state('created')->create([
            'created_at' => now()->subDays(2),
            'user_id' => $member->user->id
        ]);

        $result = $this->artisan("orders:remind-abandoned")->run();
        $this->assertSame(Command::SUCCESS, $result);

        Mail::assertQueued(function(RestoreAbandonedOrderMail $mail) use ($abandoned) {
            return $mail->order->is($abandoned) && $mail->restorationUrl() === url("/revived-orders/{$abandoned->order_key}");
        });

        Mail::assertQueued(function(RestoreAbandonedOrderMail $mail) use ($member_abandoned) {
            return $mail->order->is($member_abandoned) && $mail->restorationUrl() === url("/me/revived-orders/{$member_abandoned->order_key}");
        });

        Mail::assertNotQueued(RestoreAbandonedOrderMail::class, function(RestoreAbandonedOrderMail $mail) use ($old_abandoned) {
            return $mail->order->is($old_abandoned);
        });

        Mail::assertNotQueued(RestoreAbandonedOrderMail::class, function(RestoreAbandonedOrderMail $mail) use ($replaced) {
            return $mail->order->is($replaced);
        });


    }
}
