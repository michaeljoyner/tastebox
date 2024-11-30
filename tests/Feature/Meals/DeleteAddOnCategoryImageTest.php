<?php

namespace Tests\Feature\Meals;

use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DeleteAddOnCategoryImageTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function delete_an_existing_add_on_category_image()
    {
        $this->fakeMedia();

        $category = factory(AddOnCategory::class)->create();
        $image = $category->setImage($this->fakeJpg());

        $response = $this
            ->asAdmin()
            ->deleteJson("/admin/api/add-on-categories/{$category->uuid}/image");
        $response->assertSuccessful();

        $this->assertMediaImageMissing($image);
    }
}
