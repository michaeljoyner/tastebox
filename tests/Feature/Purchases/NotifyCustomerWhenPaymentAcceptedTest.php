<?php


namespace Tests\Feature\Purchases;


use App\Mail\OrderConfirmed;
use App\Purchases\ITNValidator;
use App\Purchases\Order;
use App\Purchases\TestITNVaildator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyCustomerWhenPaymentAcceptedTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function customer_gets_notified_when_order_confirmed()
    {
        Mail::fake();

        $this->withoutExceptionHandling();

        $validIp = gethostbyname('sandbox.payfast.co.za');
        app()->bind(ITNValidator::class, function() {
            return new TestITNVaildator(true);
        });

        $order = factory(Order::class)->state('unpaid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 39000,
            'first_name' => 'Smarty',
            'last_name' => 'Pants',
        ]);

        $response = $this->post("/payfast/notify/{$order->order_key}", $this->validITNData(), [
            'REMOTE_ADDR' => $validIp
        ]);
        $response->assertSuccessful();

        Mail::assertQueued(OrderConfirmed::class, fn ($mail) => $mail->customer_name === 'Smarty Pants');
    }

    private function validITNData(): array
    {
        return [
            "m_payment_id"     => "afb61e5b-6e5d-4857-9196-75557bf4254e",
            "pf_payment_id"    => "1098060",
            "payment_status"   => "COMPLETE",
            "item_name"        => "Tastebox order",
            "item_description" => "1 x Mealkits",
            "amount_gross"     => "390.00",
            "amount_fee"       => "-8.97",
            "amount_net"       => "381.03",
            "custom_str1"      => null,
            "custom_str2"      => null,
            "custom_str3"      => null,
            "custom_str4"      => null,
            "custom_str5"      => null,
            "custom_int1"      => null,
            "custom_int2"      => null,
            "custom_int3"      => null,
            "custom_int4"      => null,
            "custom_int5"      => null,
            "name_first"       => "Michael",
            "name_last"        => "Michael",
            "email_address"    => "joyner.michael@gmail.com",
            "merchant_id"      => "10018663",
            "signature"        => "ebdc8fdbe6dd1ee4f5f01aad39eb475a",
        ];
    }
}
