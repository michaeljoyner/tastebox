<?php


namespace Tests\Feature\Meals;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AddMealClassificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_a_category_via_artisan_command()
    {
        $this->withoutExceptionHandling();

        $response = Artisan::call('meals:classifications test');

        $this->assertDatabaseHas('classifications', [
            'name' => 'test'
        ]);
    }
}
