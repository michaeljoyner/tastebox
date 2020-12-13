<?php


namespace Tests\Feature\Purchases;


use App\Purchases\DiscountCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckOnDiscountCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function check_on_a_valid_code()
    {
        $this->withoutExceptionHandling();
        $code = factory(DiscountCode::class)->create(['type' => DiscountCode::LUMP, 'value' => 50]);

        $response = $this->asGuest()->postJson("/discount-code-status", [
            'discount_code' => $code->code,
        ]);
        $response->assertSuccessful();

        $expected = [
            'code' => $code->code,
            'is_valid' => true,
            'message' => '',
            'type' => 'lump',
            'value' => 50
        ];

        $this->assertSame($expected, $response->json());
    }

    /**
     *@test
     */
    public function check_on_an_expired_code()
    {
        $this->withoutExceptionHandling();
        $code = factory(DiscountCode::class)->state('expired')->create(['type' => DiscountCode::LUMP]);

        $response = $this->asGuest()->postJson("/discount-code-status", [
            'discount_code' => $code->code,
        ]);
        $response->assertSuccessful();

        $expected = [
            'code' => $code->code,
            'is_valid' => false,
            'message' => "{$code->code} has expired.",
            'type' => 'lump',
            'value' => $code->value,
        ];

        $this->assertSame($expected, $response->json());
    }

    /**
     *@test
     */
    public function check_on_a_used_code()
    {
        $this->withoutExceptionHandling();
        $code = factory(DiscountCode::class)->state('used')->create(['type' => DiscountCode::LUMP]);

        $response = $this->asGuest()->postJson("/discount-code-status", [
            'discount_code' => $code->code,
        ]);
        $response->assertSuccessful();

        $expected = [
            'code' => $code->code,
            'is_valid' => false,
            'message' => "{$code->code} has already been used.",
            'type' => 'lump',
            'value' => $code->value,
        ];

        $this->assertSame($expected, $response->json());
    }

    /**
     *@test
     */
    public function check_on_a_non_existing_code()
    {
        $this->withoutExceptionHandling();

        $response = $this->asGuest()->postJson("/discount-code-status", [
            'discount_code' => 'DOESNOTEXIST',
        ]);
        $response->assertSuccessful();

        $expected = [
            'code' => 'DOESNOTEXIST',
            'is_valid' => false,
            'message' => "DOESNOTEXIST is not a valid discount code.",
            'type' => null,
            'value' => null,
        ];

        $this->assertSame($expected, $response->json());
    }
}
