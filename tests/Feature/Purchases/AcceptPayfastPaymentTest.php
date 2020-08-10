<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\ITNValidator;
use App\Purchases\Order;
use App\Purchases\Payment;
use App\Purchases\ShoppingBasket;
use App\Purchases\TestITNVaildator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcceptPayfastPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        app()->bind(ITNValidator::class, function() {
            return new TestITNVaildator(true);
        });
    }

    /**
     * @test
     */
    public function accept_payment_when_notified_with_valid_itn()
    {
        $this->withoutExceptionHandling();

        $validIp = gethostbyname('sandbox.payfast.co.za');

        $order = factory(Order::class)->state('unpaid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 39000
        ]);

        $response = $this->post("/payfast/notify/{$order->order_key}", $this->validITNData(), [
            'REMOTE_ADDR' => $validIp
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id'      => $order->id,
            'is_paid' => true,
        ]);


        $this->assertDatabaseHas('payments', [
            'order_id'     => $order->id,
            'merchant'     => 'payfast',
            'payment_id'  => '1098060',
            'amount_gross' => 390 * 100,
            'amount_fee'   => 8.97 * 100,
            'amount_net'   => 381.03 * 100,
            'item'         => 'Tastebox order',
            'description'  => '1 x Mealkits',
        ]);



    }



    /**
     *@test
     */
    public function does_not_accept_payment_if_data_not_correctly_signed()
    {
        $this->withoutExceptionHandling();

        $validIp = gethostbyname('sandbox.payfast.co.za');

        $order = factory(Order::class)->state('unpaid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 39000
        ]);

        $itn_data = $this->validITNData();
        $itn_data['signature'] = 'not-a-trustworthy-signature';

        $response = $this->post("/payfast/notify/{$order->order_key}", $itn_data, [
            'REMOTE_ADDR' => $validIp
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id'      => $order->id,
            'is_paid' => false,
        ]);

        $this->assertDatabaseMissing('payments', ['order_id' => $order->id]);
    }

    /**
     *@test
     */
    public function does_not_accept_payment_from_unknown_ip()
    {
        $this->withoutExceptionHandling();

        $order = factory(Order::class)->state('unpaid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 39000
        ]);

        $response = $this->post("/payfast/notify/{$order->order_key}", $this->validITNData(), [
            'REMOTE_ADDR' => '160.52.36.194'
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id'      => $order->id,
            'is_paid' => false,
        ]);

        $this->assertDatabaseMissing('payments', ['order_id' => $order->id]);
    }

    /**
     *@test
     */
    public function does_not_process_order_is_order_already_paid()
    {
        $this->withoutExceptionHandling();

        $order = factory(Order::class)->state('paid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 39000
        ]);
        $payment = factory(Payment::class)->state('payfast')->create([
            'order_id' => $order->id,
        ]);
        $validIp = gethostbyname('sandbox.payfast.co.za');

        $response = $this->post("/payfast/notify/{$order->order_key}", $this->validITNData(), [
            'REMOTE_ADDR' => $validIp
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, Payment::where('order_id', $order->id)->get());
        $this->assertTrue($order->payment->is($payment));
    }

    /**
     *@test
     */
    public function does_not_accept_payment_for_mismatched_order()
    {
        $this->withoutExceptionHandling();

        $validIp = gethostbyname('sandbox.payfast.co.za');

        $order = factory(Order::class)->state('unpaid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 59000
        ]);

        $itn_data = $this->validITNData();

        $response = $this->post("/payfast/notify/{$order->order_key}", $itn_data, [
            'REMOTE_ADDR' => $validIp
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id'      => $order->id,
            'is_paid' => false,
        ]);

        $this->assertDatabaseMissing('payments', ['order_id' => $order->id]);
    }

    /**
     *@test
     */
    public function does_not_accept_payment_that_is_not_validated_by_payfast()
    {
        $this->withoutExceptionHandling();

        $validIp = gethostbyname('sandbox.payfast.co.za');

        $order = factory(Order::class)->state('unpaid')->create([
            'order_key'      => 'afb61e5b-6e5d-4857-9196-75557bf4254e',
            'price_in_cents' => 39000
        ]);

        $itn_data = $this->validITNData();

        app()->bind(ITNValidator::class, function() {
            return new TestITNVaildator(false);
        });

        $response = $this->post("/payfast/notify/{$order->order_key}", $itn_data, [
            'REMOTE_ADDR' => $validIp
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id'      => $order->id,
            'is_paid' => false,
        ]);

        $this->assertDatabaseMissing('payments', ['order_id' => $order->id]);
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
