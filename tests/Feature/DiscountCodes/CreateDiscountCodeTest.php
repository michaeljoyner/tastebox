<?php

namespace Tests\Feature\DiscountCodes;

use App\Purchases\DiscountCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateDiscountCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_new_discount_code()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/discount-codes", [
            'code' => 'TESTCODE',
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type' => DiscountCode::LUMP,
            'value' => 10,
            'uses' => 10
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('discount_codes', [
            'code' => 'TESTCODE',
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type' => DiscountCode::LUMP,
            'value' => 10,
            'uses' => 10
        ]);
    }

    /**
     *@test
     */
    public function handles_missing_values()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/discount-codes", [
            'code' => 'TESTCODE',
            'valid_from' => null,
            'valid_until' => null,
            'type' => DiscountCode::LUMP,
            'value' => null,
            'uses' => null
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('discount_codes', [
            'code' => 'TESTCODE',
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type' => DiscountCode::LUMP,
            'value' => 0,
            'uses' => 0
        ]);
    }

    /**
     *@test
     */
    public function the_code_is_required()
    {
        $this->assertFieldIsInvalid(['code' => null]);
    }

    /**
     *@test
     */
    public function the_discount_code_must_be_unique()
    {
        factory(DiscountCode::class)->create(['code' => 'USEDCODE']);

        $this->assertFieldIsInvalid(['code' => 'USEDCODE']);
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
    public function the_uses_must_be_a_positive_integer()
    {
        $this->assertFieldIsInvalid(['uses' => 'not-a-number']);
        $this->assertFieldIsInvalid(['uses' => 11.23]);
        $this->assertFieldIsInvalid(['uses' => 0]);
        $this->assertFieldIsInvalid(['uses' => -3]);
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

    private function assertFieldIsInvalid($field)
    {
        $valid = [
            'code' => 'TESTCODE',
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addWeek()->format('Y-m-d'),
            'type' => DiscountCode::LUMP,
            'value' => 10,
            'uses' => 10
        ];
        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/discount-codes", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
