<?php

namespace Tests\Feature\Purchases;

use App\Purchases\Adjustment;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResolveAdjustmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function mark_an_adjustment_as_resolved()
    {
        $this->withoutExceptionHandling();

        $adjustment = factory(Adjustment::class)->state('unresolved')->create();
        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin)->postJson("/admin/api/resolved-adjustments", [
            'adjustment_id' => $adjustment->id,
        ]);
        $response->assertSuccessful();

        $adjustment->refresh();

        $this->assertSame(Adjustment::STATUS_RESOLVED, $adjustment->status);
        $this->assertTrue($adjustment->resolver->is($admin));
    }
}
