<?php


namespace Tests\Unit\Purchases;


use App\Purchases\Order;
use App\Purchases\PayfastITN;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaymentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function order_can_accept_payment_from_payfast_itn()
    {
        $order = factory(Order::class)->state('unpaid')->create();
        $itn = new PayfastITN($this->validITNData());

        $order->acceptPayment($itn);

        $this->assertTrue($order->fresh()->is_paid);
        $order->refresh();

        $this->assertEquals('payfast', $order->payment->merchant);
        $this->assertEquals(1098060, $order->payment->payment_id);
        $this->assertEquals(39000, $order->payment->amount_gross);
        $this->assertEquals(897, $order->payment->amount_fee);
        $this->assertEquals(38103, $order->payment->amount_net);
        $this->assertEquals('Tastebox order', $order->payment->item);
        $this->assertEquals('1 x Mealkits', $order->payment->description);
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
