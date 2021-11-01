<?php

namespace Tests\Feature\Membership;

use App\Purchases\Discount;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateGeneralMemberDiscountsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_discount_for_all_members()
    {
        $this->withoutExceptionHandling();

        $members = factory(User::class)->state('member')->times(3)->create();

        $response = $this->asAdmin()->postJson("/admin/api/general-member-discounts", [
            'code'        => 'TESTCODE',
            'valid_from'  => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 10,
        ]);
        $response->assertSuccessful();

        $members->each(function(User $user) {
            $this->assertCount(1, $user->discounts);
            $this->assertSame('TESTCODE', $user->discounts->first()->code);
            $this->assertSame(10, $user->discounts->first()->value);
            $this->assertSame(Discount::LUMP, $user->discounts->first()->type);
            $this->assertTrue($user->discounts->first()->valid_from->isSameDay(now()));
            $this->assertTrue($user->discounts->first()->valid_until->isSameDay(now()->addWeek()));
        });
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
            'code'        => 'TESTCODE',
            'valid_from'  => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type'        => Discount::LUMP,
            'value'       => 10,
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/general-member-discounts", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
