<?php

namespace Tests\Feature\AddOns;

use App\AddOns\AddOn;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DeleteAddOnTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_add_on()
    {
        $add_on = factory(AddOn::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/add-ons/{$add_on->uuid}");
        $response->assertSuccessful();

        $this->assertModelMissing($add_on);
    }
}
