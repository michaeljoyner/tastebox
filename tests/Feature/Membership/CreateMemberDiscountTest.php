<?php

namespace Tests\Feature\Membership;

use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateMemberDiscountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_a_member_discount_for_an_existing_member()
    {
        $this->withoutExceptionHandling();

        $member = factory(User::class)->state('member')->create();

        $response = $this->asAdmin()->postJson("/admin/api/members/{$member->id}/discounts", [
            'code'        => 'TESTCODE',
            'valid_from'  => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 10,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('member_discounts', [
            'code'        => 'TESTCODE',
            'valid_from'  => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 10,
        ]);

        $this->assertNull($member->discounts->first()->discount_tag);
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
        $member = factory(User::class)->state('member')->create();

        $valid = [
            'code'        => 'TESTCODE',
            'valid_from'  => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 10,
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/members/{$member->id}/discounts", array_merge($valid, $field));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
