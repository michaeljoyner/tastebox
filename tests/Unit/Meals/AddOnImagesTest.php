<?php

namespace Tests\Unit\Meals;

use App\AddOns\AddOn;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AddOnImagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function can_set_an_image_for_add_on_categories()
    {
        $this->fakeMedia();

        $add_on = factory(AddOn::class)->create();
        $upload = $this->fakeJpg();

        $media = $add_on->setImage($upload);

        $this->assertTrue($media->model->is($add_on));
        $this->assertSame($upload->hashName(), $media->file_name);
        $this->assertSame(AddOn::IMAGE, $media->collection_name);

        $this->assertMediaImageExists($media);
        $this->assertMediaImageExists($media, "web");
        $this->assertMediaImageExists($media, "thumb");
    }

    /**
     *@test
     */
    public function can_clear_add_on_category_image()
    {
        $this->fakeMedia();

        $add_on = factory(AddOn::class)->create();
        $image = $add_on->setImage($this->fakeJpg());

        $add_on->clearImage();

        $this->assertMediaImageMissing($image);
    }

    /**
     *@test
     */
    public function setting_an_image_overwrites_any_previous_one()
    {
        $this->fakeMedia();

        $add_on = factory(AddOn::class)->create();

        $old = $add_on->setImage($this->fakeJpg());
        $new = $add_on->setImage($this->fakeJpg());

        $this->assertCount(1, $add_on->getMedia(AddOn::IMAGE));

        $this->assertMediaImageExists($new);
        $this->assertMediaImageMissing($old);
    }
}
