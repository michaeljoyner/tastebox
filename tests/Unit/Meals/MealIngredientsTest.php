<?php


namespace Tests\Unit\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealIngredientsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_organize_a_meals_ingredients()
    {
        $meal = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();

        $meal->ingredients()->sync([
            $ingredientA->id => ['in_kit' => true, 'quantity' => 'a bunch'],
            $ingredientB->id => ['in_kit' => true, 'quantity' => 'a bunch'],
            $ingredientC->id => ['in_kit' => true, 'quantity' => 'a bunch'],
            $ingredientD->id => ['in_kit' => true, 'quantity' => 'a bunch'],
        ]);

        $meal->organizeIngredients([
            ['id' => $ingredientA->id, 'position' => 3, 'group' => 'main dish'],
            ['id' => $ingredientB->id, 'position' => 1, 'group' => 'main dish'],
            ['id' => $ingredientC->id, 'position' => 0, 'group' => 'sauce'],
            ['id' => $ingredientD->id, 'position' => 2, 'group' => 'sauce'],
        ]);

        $expected = [
            [
                'id'          => $ingredientA->id,
                'description' => $ingredientA->description,
                'in_kit'      => true,
                'quantity'    => 'a bunch',
                'position'    => 3,
                'group'       => 'main dish',
                'form'        => null,
            ],
            [
                'id'          => $ingredientB->id,
                'description' => $ingredientB->description,
                'in_kit'      => true,
                'quantity'    => 'a bunch',
                'position'    => 1,
                'group'       => 'main dish',
                'form'        => null,
            ],
            [
                'id'          => $ingredientC->id,
                'description' => $ingredientC->description,
                'in_kit'      => true,
                'quantity'    => 'a bunch',
                'position'    => 0,
                'group'       => 'sauce',
                'form'        => null,
            ],
            [
                'id'          => $ingredientD->id,
                'description' => $ingredientD->description,
                'in_kit'      => true,
                'quantity'    => 'a bunch',
                'position'    => 2,
                'group'       => 'sauce',
                'form'        => null,
            ],
        ];

        $this->assertEquals($expected, $meal->fresh()->ingredients->toArray());
    }
}
