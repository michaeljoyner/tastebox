<?php

namespace Tests\Feature\Meals;

use App\AddOns\AddOn;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UploadAddOnImageTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function upload_an_image_for_an_add_on_category()
    {
        $this->fakeMedia();

        $add_on = factory(AddOn::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/add-ons/{$add_on->uuid}/image", [
            'image' => $this->fakeJpg(),
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, $add_on->getMedia(AddOn::IMAGE));
        $image = $add_on->getFirstMedia(AddOn::IMAGE);

        $this->assertSame($image->getUrl("web"), $response->json("src"));
    }
}
