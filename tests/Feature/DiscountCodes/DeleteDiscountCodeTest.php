<?php


namespace Tests\Feature\DiscountCodes;


use App\Purchases\DiscountCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteDiscountCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_discount_code()
    {
        $this->withoutExceptionHandling();

        $code = factory(DiscountCode::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/discount-codes/{$code->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('discount_codes', ['id' => $code->id]);
    }
}
