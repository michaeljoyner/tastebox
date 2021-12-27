<?php

namespace Tests\Feature\Membership;

use App\DatePresenter;
use App\Orders\DiscountCodeInfo;
use App\Purchases\Discount;
use App\Purchases\MemberDiscount;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteGeneralMemberDiscountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function delete_previously_created_member_discounts_based_on_tags()
    {
        $this->withoutExceptionHandling();

        $members = factory(User::class)->state('member')->times(3)->create();
        factory(MemberDiscount::class)->create();
        $discountInfo = new DiscountCodeInfo([
            'code'        => 'TESTCODE',
            'valid_from'  => now()->format(DatePresenter::STANDARD),
            'valid_until' => now()->addWeek()->format(DatePresenter::STANDARD),
            'type'        => Discount::LUMP,
            'value'       => 15,
        ]);

        $tag = Str::uuid()->toString();
        $members->each(fn(User $user) => $user->awardDiscount($discountInfo, $tag));

        $this->assertDatabaseCount('member_discounts', 4);

        $response = $this->asAdmin()->deleteJson("/admin/api/general-member-discounts/{$tag}");
        $response->assertSuccessful();

        $this->assertDatabaseCount('member_discounts', 1);
        $this->assertNotSame(MemberDiscount::first()->discount_tag, $tag);
    }
}
