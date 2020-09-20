<?php


namespace Tests\Unit\Meals;



use App\Meals\Ingredient;
use App\Meals\Meal;
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

    /**
     *@test
     */
    public function can_scope_to_unused()
    {
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();

        $mealA->ingredients()->sync([$ingredientA->id, $ingredientB->id]);
        $mealB->ingredients()->sync([$ingredientA->id, $ingredientD->id]);

        $scoped = Ingredient::unused()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($ingredientC));
    }
}
