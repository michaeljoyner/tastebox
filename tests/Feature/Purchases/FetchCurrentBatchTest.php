<?php


namespace Tests\Feature\Purchases;


use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchCurrentBatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function fetch_current_batch()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->state('current')->create();

        $response = $this->asAdmin()->getJson("/admin/api/current-batch");
        $response->assertSuccessful();

        $batch = $menu->getBatch();
        $expected = [
            'name'        => "Batch #{$menu->weekOfYear()}",
            'kits'        => $batch->kitList(),
            'meals'       => $batch->mealList(),
            'ingredients' => $batch->ingredientList(),
        ];

        $this->assertEquals($expected, $response->decodeResponseJson());
    }
}
