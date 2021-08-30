<?php


namespace Tests\Feature\DiscountCodes;


use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateDiscountCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_an_existing_code()
    {
        $this->withoutExceptionHandling();

        $code = factory(DiscountCode::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/discount-codes/{$code->id}", [
            'code' => 'NEWTESTCODE',
            'valid_from' => now()->addDays(3)->format('Y-m-d'),
            'valid_until' => now()->addWeeks(2)->format('Y-m-d'),
            'type' => Discount::LUMP,
            'value' => 66,
            'uses' => 88
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('discount_codes', [
            'id' => $code->id,
            'code' => 'NEWTESTCODE',
            'valid_from' => now()->addDays(3)->format('Y-m-d'),
            'valid_until' => now()->addWeeks(2)->format('Y-m-d'),
            'type' => Discount::LUMP,
            'value' => 66,
            'uses' => 88
        ]);
    }

    /**
     *@test
     */
    public function can_update_without_changing_the_code()
    {
        $this->withoutExceptionHandling();

        $code = factory(DiscountCode::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/discount-codes/{$code->id}", [
            'code' => $code->code,
            'valid_from' => now()->addDays(3)->format('Y-m-d'),
            'valid_until' => now()->addWeeks(2)->format('Y-m-d'),
            'type' => Discount::LUMP,
            'value' => 66,
            'uses' => 88
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('discount_codes', [
            'id' => $code->id,
            'code' => $code->code,
            'valid_from' => now()->addDays(3)->format('Y-m-d'),
            'valid_until' => now()->addWeeks(2)->format('Y-m-d'),
            'type' => Discount::LUMP,
            'value' => 66,
            'uses' => 88
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
        $code = factory(DiscountCode::class)->create();

        $valid = [
            'code' => 'NEWTESTCODE',
            'valid_from' => now()->addDays(3)->format('Y-m-d'),
            'valid_until' => now()->addWeeks(2)->format('Y-m-d'),
            'type' => Discount::LUMP,
            'value' => 66,
            'uses' => 88
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/discount-codes/{$code->id}", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
