<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\TestsMedia;

class UploadMealImageTest extends TestCase
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
    public function upload_image_to_meal_gallery()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images", [
            'image' => UploadedFile::fake()->image('testpic.png'),
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, $meal->getMedia(Meal::GALLERY));
        $image = $meal->fresh()->getFirstMedia(Meal::GALLERY);
        $this->assertStorageHasImage($image);

        $this->assertEquals($image->getUrl('web'), $response->json('src'));
    }

    /**
     *@test
     */
    public function the_image_is_required()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images", [
            'image' => null,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    /**
     *@test
     */
    public function the_image_must_be_a_valid_image()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images", [
            'image' => UploadedFile::fake()->create('not-an-image.txt'),
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
