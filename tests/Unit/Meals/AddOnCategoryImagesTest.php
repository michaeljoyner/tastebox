<?php

namespace Tests\Unit\Meals;

use App\AddOns\AddOnCategory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AddOnCategoryImagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function can_set_an_image_for_add_on_categories()
    {
        $this->fakeMedia();

        $category = factory(AddOnCategory::class)->create();
        $upload = $this->fakeJpg();

        $media = $category->setImage($upload);

        $this->assertTrue($media->model->is($category));
        $this->assertSame($upload->hashName(), $media->file_name);
        $this->assertSame(AddOnCategory::IMAGE, $media->collection_name);

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

        $category = factory(AddOnCategory::class)->create();
        $image = $category->setImage($this->fakeJpg());

        $category->clearImage();

        $this->assertMediaImageMissing($image);
    }

    /**
     *@test
     */
    public function setting_an_image_overwrites_any_previous_one()
    {
        $this->fakeMedia();

        $category = factory(AddOnCategory::class)->create();

        $old = $category->setImage($this->fakeJpg());
        $new = $category->setImage($this->fakeJpg());

        $this->assertCount(1, $category->getMedia(AddOnCategory::IMAGE));

        $this->assertMediaImageExists($new);
        $this->assertMediaImageMissing($old);
    }
}
