<?php


namespace Tests\Unit\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;
use Tests\TestsMedia;

class MealImagesTest extends TestCase
{
    use RefreshDatabase, TestsMedia;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('media');
    }

    /**
     *@test
     */
    public function add_image_to_meal_gallery_collection()
    {
        $meal = factory(Meal::class)->create();

        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertInstanceOf(Media::class, $image);
        $this->assertCount(1, $meal->getMedia(Meal::GALLERY));
        $this->assertStorageHasImage($image);
    }

    /**
     *@test
     */
    public function filenames_are_not_used()
    {
        $meal = factory(Meal::class)->create();

        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertStringNotContainsString('testpic', $image->getUrl());
    }

    /**
     *@test
     */
    public function new_images_get_a_default_position()
    {
        $meal = factory(Meal::class)->create();
        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertEquals(999, $image->getCustomProperty('position'));
    }

    /**
     *@test
     */
    public function a_thumb_conversion_is_generated()
    {
        $meal = factory(Meal::class)->create();

        /** @var Media $image */
        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertTrue($image->fresh()->hasGeneratedConversion('thumb'));
        $this->assertStorageHasImage($image, 'thumb');
    }

    /**
     *@test
     */
    public function a_web_conversion_is_generated()
    {
        $meal = factory(Meal::class)->create();

        /** @var Media $image */
        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertTrue($image->fresh()->hasGeneratedConversion('web'));
        $this->assertStorageHasImage($image, 'web');
    }

    /**
     *@test
     */
    public function meal_image_positions_can_be_set()
    {
        $meal = factory(Meal::class)->create();

        $imageA = $meal->addImage(UploadedFile::fake()->image('test_one.jpg'));
        $imageB = $meal->addImage(UploadedFile::fake()->image('test_two.jpg'));
        $imageC = $meal->addImage(UploadedFile::fake()->image('test_three.jpg'));
        $imageD = $meal->addImage(UploadedFile::fake()->image('test_three.jpg'));

        $meal->setGalleryPositions([$imageB->id, $imageC->id, $imageA->id]);

        $this->assertEquals(1, $imageB->fresh()->getCustomProperty('position'));
        $this->assertEquals(2, $imageC->fresh()->getCustomProperty('position'));
        $this->assertEquals(3, $imageA->fresh()->getCustomProperty('position'));
        $this->assertEquals(999, $imageD->fresh()->getCustomProperty('position'));
    }

    /**
     *@test
     */
    public function can_get_the_title_image()
    {
        $meal = factory(Meal::class)->create();

        /** @var Media $image */
        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertEquals($image->getUrl(), $meal->titleImage());
        $this->assertEquals($image->getUrl('web'), $meal->titleImage('web'));
        $this->assertEquals($image->getUrl('thumb'), $meal->titleImage('thumb'));
    }

    /**
     *@test
     */
    public function title_image_has_default()
    {
        $meal = factory(Meal::class)->create();

        $this->assertEquals(Meal::DEFAULT_IMAGE, $meal->titleImage());
        $this->assertEquals(Meal::DEFAULT_IMAGE, $meal->titleImage('web'));
        $this->assertEquals(Meal::DEFAULT_IMAGE, $meal->titleImage('thumb'));
    }
}
