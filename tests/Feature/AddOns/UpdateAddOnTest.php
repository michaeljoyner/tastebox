<?php

namespace Tests\Feature\AddOns;

use App\AddOns\AddOn;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UpdateAddOnTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function update_an_existing_add_on()
    {
        $add_on = factory(AddOn::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/add-ons/{$add_on->uuid}", [
            'name' => 'updated name',
            'description' => 'updated description',
        ]);
        $response->assertSuccessful();

        $this->assertSame("updated name", $add_on->fresh()->name);
        $this->assertSame("updated description", $add_on->fresh()->description);
    }
}
