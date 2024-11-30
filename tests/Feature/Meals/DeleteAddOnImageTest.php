<?php

namespace Tests\Feature\Meals;

use App\AddOns\AddOn;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DeleteAddOnImageTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_add_on_image()
    {
        $this->fakeMedia();

        $add_on = factory(AddOn::class)->create();
        $image = $add_on->setImage($this->fakeJpg());

        $response = $this->asAdmin()->deleteJson("/admin/api/add-ons/{$add_on->uuid}/image");
        $response->assertSuccessful();

        $this->assertMediaImageMissing($image);
    }
}
