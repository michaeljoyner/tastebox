<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\NullDiscount;
use App\Purchases\Order;
use App\Purchases\PayFast;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayFastTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_checkout_form_from_an_order()
    {
        config(['payfast.merchant_id' => '12345']);
        config(['payfast.merchant_key' => 'abcde']);
        config(['payfast.return_url' => 'https://tastebox.test/payfast/return']);
        config(['payfast.cancel_url' => 'https://tastebox.test/payfast/cancel']);
        config(['payfast.notify_url' => 'https://tastebox.test/payfast/notify']);
        config(['payfast.passphrase' => 'salty_dog']);

        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id, $mealD->id]);

        $basket = ShoppingBasket::for(null);
        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);

        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);

        $kitB->setMeal($mealC->id, 4);
        $kitB->setMeal($mealD->id, 5);

        $customer = [
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'phone' => '0791112223',
            'email' => 'test@test.test',
        ];

        $order = Order::makeNew($customer, $basket->kits, new NullDiscount());


        $order_data = [
            'merchant_id' => '12345',
            'merchant_key' => 'abcde',
            'return_url' => 'https://tastebox.test/payfast/return/' . $order->order_key,
            'cancel_url' => 'https://tastebox.test/payfast/cancel/' . $order->order_key,
            'notify_url' => 'https://tastebox.test/payfast/notify/' . $order->order_key,
            'name_first' => 'test first name',
            'name_last' => 'test last name',
            'email_address' => 'test@test.test',
            'cell_number' => '0791112223',
            'm_payment_id' => $order->order_key,
            'amount' => $basket->price(),
            'item_name' => 'Tastebox order',
            'item_description' => '2 x Mealkits',
            'email_confirmation' => 0,
        ];

        $out = collect($order_data)
            ->map(function($value, $key) {
                return sprintf("%s=%s", $key, urlencode($value));
            })->join("&");

        $sig = md5($out . "&passphrase=salty_dog");

        $expected = array_merge($order_data, ['signature' => $sig]);

        $this->assertEquals($expected, PayFast::checkoutForm($order));

    }

    /**
     *@test
     */
    public function will_not_include_cell_number_if_not_a_valid_sa_cell_number()
    {
        config(['payfast.merchant_id' => '12345']);
        config(['payfast.merchant_key' => 'abcde']);
        config(['payfast.return_url' => 'https://tastebox.test/payfast/return']);
        config(['payfast.cancel_url' => 'https://tastebox.test/payfast/cancel']);
        config(['payfast.notify_url' => 'https://tastebox.test/payfast/notify']);
        config(['payfast.passphrase' => 'salty_dog']);

        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id, $mealD->id]);

        $basket = ShoppingBasket::for(null);
        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);

        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);

        $kitB->setMeal($mealC->id, 4);
        $kitB->setMeal($mealD->id, 5);

        $customer = [
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'phone' => '555555555',
            'email' => 'test@test.test',
        ];

        $order = Order::makeNew($customer, $basket->kits, new NullDiscount());

        $order_data = [
            'merchant_id' => '12345',
            'merchant_key' => 'abcde',
            'return_url' => 'https://tastebox.test/payfast/return/' . $order->order_key,
            'cancel_url' => 'https://tastebox.test/payfast/cancel/' . $order->order_key,
            'notify_url' => 'https://tastebox.test/payfast/notify/' . $order->order_key,
            'name_first' => 'test first name',
            'name_last' => 'test last name',
            'email_address' => 'test@test.test',
            'm_payment_id' => $order->order_key,
            'amount' => $basket->price(),
            'item_name' => 'Tastebox order',
            'item_description' => '2 x Mealkits',
            'email_confirmation' => 0,
        ];

        $out = collect($order_data)
            ->map(function($value, $key) {
                return sprintf("%s=%s", $key, urlencode($value));
            })->join("&");

        $sig = md5($out . "&passphrase=salty_dog");

        $expected = array_merge($order_data, ['signature' => $sig]);

        $this->assertEquals($expected, PayFast::checkoutForm($order));
    }

    /**
     *@test
     */
    public function checks_if_response_correctly_signed()
    {
        $response_data = [
            "m_payment_id" => "afb61e5b-6e5d-4857-9196-75557bf4254e",
            "pf_payment_id" => "1098060",
            "payment_status" => "COMPLETE",
            "item_name" => "Tastebox order",
            "item_description" => "1 x Mealkits",
            "amount_gross" => "390.00",
            "amount_fee" => "-8.97",
            "amount_net" => "381.03",
            "custom_str1" => null,
            "custom_str2" => null,
            "custom_str3" => null,
            "custom_str4" => null,
            "custom_str5" => null,
            "custom_int1" => null,
            "custom_int2" => null,
            "custom_int3" => null,
            "custom_int4" => null,
            "custom_int5" => null,
            "name_first" => "Michael",
            "name_last" => "Michael",
            "email_address" => "joyner.michael@gmail.com",
            "merchant_id" => "10018663",
            "signature" => "ebdc8fdbe6dd1ee4f5f01aad39eb475a",
        ];

        $modified_data = [
            "m_payment_id" => "afb61e5b-6e5d-4857-9196-75557bf4254e",
            "pf_payment_id" => "1098060",
            "payment_status" => "COMPLETE",
            "item_name" => "Tastebox order",
            "item_description" => "1 x Mealkits",
            "amount_gross" => "390.00",
            "amount_fee" => "-8.97",
            "amount_net" => "1.03",
            "custom_str1" => null,
            "custom_str2" => null,
            "custom_str3" => null,
            "custom_str4" => null,
            "custom_str5" => null,
            "custom_int1" => null,
            "custom_int2" => null,
            "custom_int3" => null,
            "custom_int4" => null,
            "custom_int5" => null,
            "name_first" => "Michael",
            "name_last" => "Michael",
            "email_address" => "joyner.michael@gmail.com",
            "merchant_id" => "10018663",
            "signature" => "ebdc8fdbe6dd1ee4f5f01aad39eb475a",
        ];

        $this->assertTrue(PayFast::checkSignature($response_data));
        $this->assertFalse(PayFast::checkSignature($modified_data));


    }


}
