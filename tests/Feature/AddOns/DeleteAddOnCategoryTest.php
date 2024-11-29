<?php

namespace Tests\Feature\AddOns;

use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DeleteAddOnCategoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_add_on_category()
    {
        $category = factory(AddOnCategory::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/add-on-categories/{$category->uuid}");
        $response->assertSuccessful();

        $this->assertModelMissing($category);
    }
}
