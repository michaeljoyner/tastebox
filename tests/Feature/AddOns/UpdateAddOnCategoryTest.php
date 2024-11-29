<?php

namespace Tests\Feature\AddOns;

use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UpdateAddOnCategoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function can_update_an_existing_add_on_category()
    {
        $category = factory(AddOnCategory::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/add-on-categories/{$category->uuid}", [
            'name' => 'updated name',
            'description' => 'updated description',
        ]);
        $response->assertSuccessful();

        $this->assertSame("updated name", $category->fresh()->name);
        $this->assertSame("updated description", $category->fresh()->description);
    }
}
