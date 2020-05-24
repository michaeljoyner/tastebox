<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateMealImagePositionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('media');
    }

    /**
     *@test
     */
    public function update_positions_of_meal_images()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();
        $imageA = $meal->addImage(UploadedFile::fake()->image('one.jpg'));
        $imageB = $meal->addImage(UploadedFile::fake()->image('two.jpg'));
        $imageC = $meal->addImage(UploadedFile::fake()->image('three.jpg'));

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images/positions", [
            'image_ids' => [$imageC->id, $imageA->id, $imageB->id],
        ]);
        $response->assertSuccessful();

        $this->assertEquals(1, $imageC->fresh()->getCustomProperty('position'));
        $this->assertEquals(2, $imageA->fresh()->getCustomProperty('position'));
        $this->assertEquals(3, $imageB->fresh()->getCustomProperty('position'));
    }

    /**
     *@test
     */
    public function image_ids_are_required()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images/positions", []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image_ids');
    }

    /**
     *@test
     */
    public function image_ids_must_me_an_array()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images/positions", [
            'image_ids' => 'not-an-array',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image_ids');
    }

    /**
     *@test
     */
    public function the_image_ids_must_exist_in_media_table()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/images/positions", [
            'image_ids' => [99],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image_ids.0');
    }
}
