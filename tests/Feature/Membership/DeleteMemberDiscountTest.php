<?php

namespace Tests\Feature\Membership;

use App\Purchases\MemberDiscount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteMemberDiscountTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_discount()
    {
        $this->withoutExceptionHandling();

        $discount = factory(MemberDiscount::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/member-discounts/{$discount->id}");
        $response->assertSuccessful();

        $this->assertModelMissing($discount);
    }
}
