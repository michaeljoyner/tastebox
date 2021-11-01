<?php

namespace Tests\Feature\Membership;

use App\Purchases\Discount;
use App\Purchases\MemberDiscount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateMemberDiscountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_an_existing_member_discount()
    {
        $this->withoutExceptionHandling();

        $discount = factory(MemberDiscount::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/member-discounts/{$discount->id}", [
            'code'        => 'NEWTEST',
            'valid_from'  => now()->addWeek()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 66,
        ]);
        $response->assertSuccessful();


        $this->assertDatabaseHas('member_discounts', [
            'id'          => $discount->id,
            'code'        => 'NEWTEST',
            'valid_from'  => now()->addWeek()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 66,
        ]);

    }

    /**
     *@test
     */
    public function cannot_update_a_tagged_member_discount()
    {
        $discount = factory(MemberDiscount::class)->state('tagged')->create();

        $response = $this->asAdmin()->postJson("/admin/api/member-discounts/{$discount->id}", [
            'code'        => 'NEWTEST',
            'valid_from'  => now()->addWeek()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 66,
        ]);
        $response->assertForbidden();
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
        $discount = factory(MemberDiscount::class)->create();

        $valid = [
            'code'        => 'NEWTEST',
            'valid_from'  => now()->addWeek()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 66,
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/member-discounts/{$discount->id}", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
