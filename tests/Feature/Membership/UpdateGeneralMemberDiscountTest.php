<?php

namespace Tests\Feature\Membership;

use App\DatePresenter;
use App\Orders\DiscountCodeInfo;
use App\Purchases\Discount;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateGeneralMemberDiscountTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_existing_member_discounts_with_certain_tag()
    {
        $this->withoutExceptionHandling();

        $members = factory(User::class)->state('member')->times(3)->create();
        $discountInfo = new DiscountCodeInfo([
            'code' => 'TESTCODE',
            'valid_from' => now()->format(DatePresenter::STANDARD),
            'valid_until' => now()->addWeek()->format(DatePresenter::STANDARD),
            'type' => Discount::LUMP,
            'value' => 15,
        ]);
        $tag = Str::uuid()->toString();
        $members->each(fn(User $user) => $user->awardDiscount($discountInfo, $tag));

        $response = $this->asAdmin()->postJson("/admin/api/general-member-discounts/{$tag}", [
            'code' => 'TESTCODE2',
            'valid_from' => now()->addWeek()->format(DatePresenter::STANDARD),
            'valid_until' => now()->addWeeks(2)->format(DatePresenter::STANDARD),
            'type' => Discount::PERCENTAGE,
            'value' => 22,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('member_discounts', [
            'code' => 'TESTCODE2',
            'valid_from' => now()->addWeek()->format(DatePresenter::STANDARD),
            'valid_until' => now()->addWeeks(2)->format(DatePresenter::STANDARD),
            'type' => Discount::PERCENTAGE,
            'value' => 22,
            'discount_tag' => $tag,
        ]);

        $this->assertDatabaseMissing('member_discounts', [
            'code' => 'TESTCODE',
            'valid_from' => now()->format(DatePresenter::STANDARD),
            'valid_until' => now()->addWeek()->format(DatePresenter::STANDARD),
            'type' => Discount::LUMP,
            'value' => 15,
            'discount_tag' => $tag,
        ]);
    }

    /**
     *@test
     */
    public function the_code_is_required_as_alphanumeric_string()
    {
        $this->assertFieldIsInvalid(['code' => null]);
        $this->assertFieldIsInvalid(['code' => '#$hoo test']);
    }



    /**
     *@test
     */
    public function the_valid_from_must_be_a_date()
    {
        $this->assertFieldIsInvalid(['valid_from' => 'not-a-date']);
    }

    /**
     *@test
     */
    public function the_valid_until_must_be_a_date()
    {
        $this->assertFieldIsInvalid(['valid_until' => 'not-a-date']);
    }

    /**
     *@test
     */
    public function valid_until_must_be_after_or_same_as_valid_from()
    {
        $this->assertFieldIsInvalid([
            'valid_until' => now()->subDay()->format('Y-m-d'),
            'valid_from' => now()->format('Y-m-d'),
        ]);
    }

    /**
     *@test
     */
    public function the_type_must_be_one_of_the_valid_types()
    {
        $this->assertFieldIsInvalid(['type' => 'not-a-valid-type']);
    }



    /**
     *@test
     */
    public function the_value_must_be_a_positive_integer()
    {
        $this->assertFieldIsInvalid(['value' => 'not-a-number']);
        $this->assertFieldIsInvalid(['value' => 11.23]);
        $this->assertFieldIsInvalid(['value' => 0]);
        $this->assertFieldIsInvalid(['value' => -3]);
    }

    private function assertFieldIsInvalid(array $field)
    {
        $valid = [
            'code' => 'TESTCODE2',
            'valid_from' => now()->addWeek()->format(DatePresenter::STANDARD),
            'valid_until' => now()->addWeeks(2)->format(DatePresenter::STANDARD),
            'type' => Discount::PERCENTAGE,
            'value' => 22,
        ];

        $tag = Str::uuid()->toString();
        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/general-member-discounts/{$tag}", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
