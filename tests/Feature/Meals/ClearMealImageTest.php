<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\TestsMedia;

class ClearMealImageTest extends TestCase
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
    public function delete_a_meal_image()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();
        $image = $meal->addImage(UploadedFile::fake()->image('testpic.jpg'));

        $response = $this->asAdmin()->deleteJson("/admin/api/meals/{$meal->id}/images/{$image->id}");
        $response->assertSuccessful();

        $this->assertStorageDoesNotHaveImage($image);
    }

    /**
     *@test
     */
    public function the_image_must_belong_to_the_meal()
    {
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $image = $mealA->addImage(UploadedFile::fake()->image('testpic.jpg'));

        $response = $this->asAdmin()->deleteJson("/admin/api/meals/{$mealB->id}/images/{$image->id}");
        $response->assertStatus(422);

        $this->assertStorageHasImage($image);
    }
}
