<?php


namespace Tests\Unit\Meals;



use App\Meals\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_new_ingredient()
    {
        $ingredient = Ingredient::addNew('test ingredient');

        $this->assertInstanceOf(Ingredient::class, $ingredient);
        $this->assertEquals('test ingredient', $ingredient->description);
    }

    /**
     *@test
     */
    public function trying_to_add_existing_ingredient_returns_that_ingredient()
    {
        $ingredient = Ingredient::addNew('test ingredient');
        $this->assertCount(1, Ingredient::all());

        $later = Ingredient::addNew('test ingredient');
        $this->assertCount(1, Ingredient::all());

        $this->assertTrue($later->is($ingredient));
    }
}
