<?php

namespace Tests\Feature\Membership;

use App\Purchases\Discount;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateMemberDiscountTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
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
    }
}
