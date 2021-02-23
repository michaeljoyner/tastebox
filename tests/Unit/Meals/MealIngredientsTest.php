<?php


namespace Tests\Unit\Meals;


use App\Meals\Ingredient;
use App\Meals\IngredientList;
use App\Meals\Meal;
use App\Meals\MealsPresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealIngredientsTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     */
    public function meal_can_have_same_ingredient_in_different_combos()
    {
        $meal = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();

        $list = new IngredientList([
            [
                'id'       => $ingredientA->id,
                'quantity' => '10g',
                'in_kit'   => true,
                'form'     => 'test form',
                'group'    => 'test group',
                'bundled'  => false,
            ],
            [
                'id'       => $ingredientA->id,
                'quantity' => '20g',
                'in_kit'   => true,
                'form'     => 'test form',
                'group'    => 'main',
                'bundled'  => false,
            ],
            [
                'id'       => $ingredientB->id,
                'quantity' => '10g',
                'in_kit'   => true,
                'form'     => 'test form',
                'group'    => 'test group',
                'bundled'  => false,
            ],
        ]);

        $meal->setIngredients($list);

        $this->assertCount(3, $meal->fresh()->ingredients);

        collect($list->ingredients)
            ->each(fn($ingredient) => $this->assertTrue($meal->ingredients->contains(
                fn($i) => $i->pivot->quantity === $ingredient['quantity']
                    && $i->pivot->form === $ingredient['form']
                    && $i->pivot->in_kit === $ingredient['in_kit']
                    && $i->pivot->group === $ingredient['group']
                    && $i->pivot->bundled === $ingredient['bundled']
            )));

    }

    /**
     * @test
     */
    public function can_organise_meal_ingredients_with_duplicated_meal_ingredients()
    {
        $meal = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();

        $list = new IngredientList([
            [
                'id'       => $ingredientA->id,
                'quantity' => '10g',
                'in_kit'   => true,
                'form'     => 'test form',
                'group'    => 'test group',
                'bundled'  => false,
            ],
            [
                'id'       => $ingredientA->id,
                'quantity' => '20g',
                'in_kit'   => true,
                'form'     => 'test form',
                'group'    => 'main',
                'bundled'  => false,
            ],
            [
                'id'       => $ingredientB->id,
                'quantity' => '10g',
                'in_kit'   => true,
                'form'     => 'test form',
                'group'    => 'test group',
                'bundled'  => false,
            ],
        ]);

        $meal->setIngredients($list);

        $meal_ingredients = collect($meal->ingredients->toArray());

        $new_organisation = collect([
            [
                'id'                 => $meal_ingredients[0]['id'],
                'meal_ingredient_id' => $meal_ingredients[0]['meal_ingredient_id'],
                'position'           => 1,
                'group'              => 'main',
                'bundled'            => false,
            ],
            [
                'id'                 => $meal_ingredients[1]['id'],
                'meal_ingredient_id' => $meal_ingredients[1]['meal_ingredient_id'],
                'position'           => 3,
                'group'              => 'side',
                'bundled'            => true,
            ],
            [
                'id'                 => $meal_ingredients[2]['id'],
                'meal_ingredient_id' => $meal_ingredients[2]['meal_ingredient_id'],
                'position'           => 2,
                'group'              => 'main',
                'bundled'            => false,
            ],
        ]);

        $meal->organizeIngredients($new_organisation->all());

        $meal->refresh();

        $new_organisation->each(fn($ingredient) => $this->assertTrue(
            $meal->ingredients->contains(
                fn($i) => $i->pivot->group === $ingredient['group']
                    && $i->pivot->position === $ingredient['position']
                    && $i->pivot->bundled === $ingredient['bundled']
                    && $i->pivot->id === $ingredient['meal_ingredient_id']
            )
        ));


    }

    /**
     * @test
     */
    public function can_present_ingredients_for_recipe_card()
    {
        $meal = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $list = new IngredientList([
            [
                'id'       => $ingredientA->id,
                'in_kit'   => true,
                'quantity' => '100g',
                'form'     => '',
                'group'    => ''
            ],
            [
                'id'       => $ingredientB->id,
                'in_kit'   => true,
                'quantity' => '100g',
                'form'     => '',
                'group'    => ''
            ],
            [
                'id'       => $ingredientB->id,
                'in_kit'   => true,
                'quantity' => '200g',
                'form'     => '',
                'group'    => ''
            ],
            [
                'id'       => $ingredientC->id,
                'in_kit'   => true,
                'quantity' => '100g',
                'form'     => '',
                'group'    => ''
            ],
            [
                'id'       => $ingredientD->id,
                'in_kit'   => true,
                'quantity' => '100g',
                'form'     => '',
                'group'    => ''
            ],
            [
                'id'       => $ingredientE->id,
                'in_kit'   => true,
                'quantity' => '100g',
                'form'     => '',
                'group'    => ''
            ],
        ]);

        $meal->setIngredients($list);

        $meal_ingredients = $meal->fresh()->ingredients->toArray();
        $organization = collect([
            [
                'id'                 => $meal_ingredients[0]['id'],
                'meal_ingredient_id' => $meal_ingredients[0]['meal_ingredient_id'],
                'group'              => 'main',
                'bundled'            => false,
                'position'           => 1,
            ],
            [
                'id'                 => $meal_ingredients[1]['id'],
                'meal_ingredient_id' => $meal_ingredients[1]['meal_ingredient_id'],
                'group'              => 'main',
                'bundled'            => false,
                'position'           => 2,
            ],
            [
                'id'                 => $meal_ingredients[2]['id'],
                'meal_ingredient_id' => $meal_ingredients[2]['meal_ingredient_id'],
                'group'              => 'side',
                'bundled'            => false,
                'position'           => 3,
            ],
            [
                'id'                 => $meal_ingredients[3]['id'],
                'meal_ingredient_id' => $meal_ingredients[3]['meal_ingredient_id'],
                'group'              => 'main',
                'bundled'            => false,
                'position'           => 4,
            ],
            [
                'id'                 => $meal_ingredients[4]['id'],
                'meal_ingredient_id' => $meal_ingredients[4]['meal_ingredient_id'],
                'group'              => 'sauce',
                'bundled'            => true,
                'position'           => 5,
            ],
            [
                'id'                 => $meal_ingredients[5]['id'],
                'meal_ingredient_id' => $meal_ingredients[5]['meal_ingredient_id'],
                'group'              => 'sauce',
                'bundled'            => true,
                'position'           => 6,
            ],
        ]);

        $meal->organizeIngredients($organization->all());


        $expected = [

            'main' => [
                $ingredientA->description,
                $ingredientB->description,
                $ingredientC->description,
                'sauce',
            ],
            'side' => [
                $ingredientB->description,
            ],
        ];
        $this->assertSame($expected, $meal->recipeCardIngredients());
    }
}
