<?php

namespace Tests\Feature\AddOns;

use App\AddOns\AddOn;
use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateAddOnTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function create_a_new_add_on_for_category()
    {
        $this->withoutExceptionHandling();

        $category = factory(AddOnCategory::class)->create();

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/add-on-categories/{$category->uuid}/add-ons", [
                "name" => "test name",
                "description" => "test description",
                "price" => 9950
            ]);
        $response->assertSuccessful();

        $this->assertCount(1, AddOn::all());
        $add_on = AddOn::first();

        $this->assertSame("test name", $add_on->name);
        $this->assertSame("test description", $add_on->description);
        $this->assertSame(9950, $add_on->price);
        $this->assertTrue(Str::isUuid($add_on->uuid));
        $this->assertTrue($add_on->category->is($category));
    }
}
