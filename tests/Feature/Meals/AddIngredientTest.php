<?php


namespace Tests\Feature\Meals;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddIngredientTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_an_ingredient()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/ingredients/", [
            'description' => 'test ingredient',
        ]);
        $response->assertSuccessful();

        $this->assertEquals('test ingredient', $response->json('description'));
        $this->assertNotNull($response->json('id'));

        $this->assertDatabaseHas('ingredients', [
            'description' => 'test ingredient',
        ]);
    }
}
