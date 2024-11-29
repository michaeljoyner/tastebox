<?php

namespace Tests\Feature\AddOns;

use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateAddOnCategoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function create_a_category_for_add_ons()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/add-on-categories", [
            "name" => "test name",
            "description" => "test description",
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, AddOnCategory::all());
        $category = AddOnCategory::first();

        $this->assertSame("test name", $category->name);
        $this->assertTrue(Str::isUuid($category->uuid));
    }
}
