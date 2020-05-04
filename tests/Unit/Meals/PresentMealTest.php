<?php

namespace tests\Unit\Meals;

use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresentMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function present_as_array_for_admin_use()
    {
        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $meal = factory(Meal::class)->create([
            'name'            => 'test name',
            'description'     => 'test description',
            'allergens'       => 'test allergens',
            'prep_time'       => 100,
            'cook_time'       => 250,
            'instructions'    => 'test instructions',
            'serving_energy'  => '150',
            'serving_carbs'   => 50,
            'serving_fat'     => 70,
            'serving_protein' => 0,
        ]);

        $meal->ingredients()->sync([
            $ingredientA->id => ['quantity' => '2', 'in_kit' => false],
            $ingredientB->id => ['quantity' => '3 tsp', 'in_kit' => false],
            $ingredientC->id => ['quantity' => '1 bag', 'in_kit' => true],
            $ingredientD->id => ['quantity' => null, 'in_kit' => true],
            $ingredientE->id => ['quantity' => null, 'in_kit' => true],
        ]);

        $expected = [
            'id'                   => $meal->id,
            'unique_id'            => $meal->unique_id,
            'name'                 => 'test name',
            'description'          => 'test description',
            'allergens'            => 'test allergens',
            'prep_time'            => 100,
            'cook_time'            => 250,
            'instructions'         => 'test instructions',
            'serving_energy'       => '150',
            'serving_carbs'        => 50,
            'serving_fat'          => 70,
            'serving_protein'      => 0,
            'ingredients'          => [
                [
                    'id'          => $ingredientA->id,
                    'description' => $ingredientA->description,
                    'quantity'    => '2',
                    'in_kit'      => false
                ],
                [
                    'id'          => $ingredientB->id,
                    'description' => $ingredientB->description,
                    'quantity'    => '3 tsp',
                    'in_kit'      => false
                ],
                [
                    'id'          => $ingredientC->id,
                    'description' => $ingredientC->description,
                    'quantity'    => '1 bag',
                    'in_kit'      => true
                ],
                [
                    'id'          => $ingredientD->id,
                    'description' => $ingredientD->description,
                    'quantity'    => null,
                    'in_kit'      => true
                ],
                [
                    'id'          => $ingredientE->id,
                    'description' => $ingredientE->description,
                    'quantity'    => null,
                    'in_kit'      => true
                ],
            ],

        ];

        $this->assertEquals($expected, $meal->fresh()->asArrayForAdmin());
    }
}
