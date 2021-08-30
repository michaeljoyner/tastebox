<?php


namespace Tests\Unit\Purchases;


use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use App\Purchases\NullDiscount;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountCodesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function discount_codes_can_discount_prices_correctly()
    {
        $null_code = new NullDiscount();

        $this->assertSame(0, $null_code->discount(0));
        $this->assertSame(88, $null_code->discount(88));

        $lump = factory(DiscountCode::class)->create(['type' => Discount::LUMP, 'value' => 50]);

        $this->assertSame(0, $lump->discount(5000));
        $this->assertSame(0, $lump->discount(3300));
        $this->assertSame(100, $lump->discount(5100));
        $this->assertSame(8800, $lump->discount(13800));

        $percent = factory(DiscountCode::class)->create(['type' => Discount::PERCENTAGE, 'value' => 5]);

        $this->assertSame(95, $percent->discount(100));
        $this->assertSame(316, $percent->discount(333));
        $this->assertSame(422, $percent->discount(444));
        $this->assertSame(0, $percent->discount(0));
    }

    /**
     *@test
     */
    public function it_is_valid_until_the_end_of_the_day()
    {
        $discount = factory(DiscountCode::class)->create([
            'valid_from' => Carbon::yesterday(),
            'valid_until' => Carbon::today(),
        ]);

        $this->assertTrue($discount->isValid());
    }
}
