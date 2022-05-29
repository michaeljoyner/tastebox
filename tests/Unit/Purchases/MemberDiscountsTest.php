<?php

namespace Tests\Unit\Purchases;

use App\Purchases\Discount;
use App\Purchases\MemberDiscount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDiscountsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_member_discount_is_a_discount()
    {
        $member_discount = factory(MemberDiscount::class)->create();

        $this->assertInstanceOf(Discount::class, $member_discount);
    }

    /**
     *@test
     */
    public function member_discount_is_validated_on_dates()
    {
        $current = factory(MemberDiscount::class)->create([
            'valid_from' => now()->subWeek(),
            'valid_until' => now()->addWeek(),
        ]);

        $expired = factory(MemberDiscount::class)->create([
            'valid_from' => now()->subWeek(),
            'valid_until' => now()->subDay(),
        ]);

        $upcoming = factory(MemberDiscount::class)->create([
            'valid_from' => now()->addDay(),
            'valid_until' => now()->addWeek(),
        ]);

        $this->assertTrue($current->isValid());
        $this->assertFalse($expired->isValid());
        $this->assertFalse($upcoming->isValid());
    }

    /**
     *@test
     */
    public function using_a_discount_will_delete_it()
    {
        $member_discount = factory(MemberDiscount::class)->create();

        $member_discount->use();

        $this->assertModelMissing($member_discount);
    }

    /**
     *@test
     */
    public function it_calculates_the_correct_discount_amount()
    {
        $lump = factory(MemberDiscount::class)->create([
            'type' => Discount::LUMP,
            'value' => 66,
        ]);
        $percent = factory(MemberDiscount::class)->create([
            'type' => Discount::PERCENTAGE,
            'value' => 20
        ]);

        $this->assertSame(0, $lump->discount(5000));
        $this->assertSame(0, $lump->discount(6600));
        $this->assertSame(3400, $lump->discount(10000));

        $this->assertSame(4, $percent->discount(5));
        $this->assertSame(8000, $percent->discount(10000));
        $this->assertSame(1320, $percent->discount(1650));
    }
}
