<?php

namespace Tests\Feature\Meals;

use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UploadAddOnCategoryImageTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function upload_image_for_add_on_category()
    {
        $this->fakeMedia();

        $category = factory(AddOnCategory::class)->create();

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/add-on-categories/{$category->uuid}/image", [
                'image' => $this->fakeJpg()
            ]);
        $response->assertSuccessful();

        $this->assertCount(1, $category->getMedia(AddOnCategory::IMAGE));
        $image = $category->getFirstMedia(AddOnCategory::IMAGE);

        $this->assertSame($image->getUrl("web"), $response->json("src"));
    }
}
