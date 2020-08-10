<?php


namespace Tests\Unit\Purchases;


use App\Meals\Ingredient;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuBatchIngredientsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function get_the_ingredients_list_for_a_batch()
    {
        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create(['description' => 'ingredient_A']);
        $ingredientB = factory(Ingredient::class)->create(['description' => 'ingredient_B']);
        $ingredientC = factory(Ingredient::class)->create(['description' => 'ingredient_C']);
        $ingredientD = factory(Ingredient::class)->create(['description' => 'ingredient_D']);
        $ingredientE = factory(Ingredient::class)->create(['description' => 'ingredient_E']);
        $ingredientF = factory(Ingredient::class)->create(['description' => 'ingredient_F']);
        $ingredientG = factory(Ingredient::class)->create(['description' => 'ingredient_G']);
        $ingredientH = factory(Ingredient::class)->create(['description' => 'ingredient_H']);
        $ingredientI = factory(Ingredient::class)->create(['description' => 'ingredient_I']);
        $ingredientJ = factory(Ingredient::class)->create(['description' => 'ingredient_J']);

        $mealA->ingredients()->sync([
            $ingredientA->id => ['quantity' => '4', 'in_kit' => true],
            $ingredientB->id => ['quantity' => '1/2 cup', 'in_kit' => true],
            $ingredientC->id => ['quantity' => 'a pound', 'in_kit' => true],
            $ingredientD->id => ['quantity' => '400g', 'in_kit' => true],
        ]);

        $mealB->ingredients()->sync([
            $ingredientE->id => ['quantity' => '20g', 'in_kit' => true],
            $ingredientF->id => ['quantity' => '1/2 tsp', 'in_kit' => false],
            $ingredientG->id => ['quantity' => 'a pack', 'in_kit' => true],
            $ingredientH->id => ['quantity' => '260ml', 'in_kit' => true],
        ]);

        $mealC->ingredients()->sync([
            $ingredientI->id => ['quantity' => '250g', 'in_kit' => true],
            $ingredientJ->id => ['quantity' => '1 can', 'in_kit' => true],
            $ingredientA->id => ['quantity' => 'a bag', 'in_kit' => true],
            $ingredientC->id => ['quantity' => '340ml', 'in_kit' => true],
        ]);

        $mealD->ingredients()->sync([
            $ingredientE->id => ['quantity' => '2kg', 'in_kit' => true],
            $ingredientG->id => ['quantity' => '1/2 box', 'in_kit' => true],
            $ingredientI->id => ['quantity' => 'a pinch', 'in_kit' => false],
            $ingredientD->id => ['quantity' => '22lb', 'in_kit' => true],
        ]);

        $mealE->ingredients()->sync([
            $ingredientB->id => ['quantity' => '3 oz', 'in_kit' => true],
            $ingredientC->id => ['quantity' => '3 cup', 'in_kit' => true],
            $ingredientG->id => ['quantity' => '4', 'in_kit' => true],
            $ingredientJ->id => ['quantity' => '6 spoons', 'in_kit' => true],
        ]);

        $menu->setMeals([
            $mealA->id,
            $mealB->id,
            $mealC->id,
            $mealD->id,
            $mealE->id,
        ]);

        $orderA = factory(Order::class)->state('paid')->create();
        $orderB = factory(Order::class)->state('paid')->create();
        $orderC = factory(Order::class)->state('paid')->create();

        $basket = ShoppingBasket::for(null);
        $kitA = $basket->addKit($menu->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);
        $kitA->setMeal($mealC->id, 4);

        $kitB = $basket->addKit($menu->id);
        $kitB->setMeal($mealC->id, 5);
        $kitB->setMeal($mealD->id, 6);
        $kitB->setMeal($mealE->id, 3);

        $kitC = $basket->addKit($menu->id);
        $kitC->setMeal($mealA->id, 3);
        $kitC->setMeal($mealC->id, 2);
        $kitC->setMeal($mealE->id, 3);

        $test_address = new Address([
            'line_one'    => '123 Test rd',
            'line_two'    => 'Fakerton',
            'city'        => 'Testville',
            'postal_code' => '3201',
        ]);

        $orderA->addKit($kitA,$test_address);
        $orderB->addKit($kitB,$test_address);
        $orderC->addKit($kitC,$test_address);

        $batch = $menu->getBatch();

        $exoected = [
            [
                'id' => $ingredientA->id,
                'description' => $ingredientA->description,
                'uses' => [
                    ['meal' => $mealA->name, 'count' => 5, 'quantity' => '4'],
                    ['meal' => $mealC->name, 'count' => 11, 'quantity' => 'a bag'],
                ]
            ],
            [
                'id' => $ingredientB->id,
                'description' => $ingredientB->description,
                'uses' => [
                    ['meal' => $mealA->name, 'count' => 5, 'quantity' => '1/2 cup'],
                    ['meal' => $mealE->name, 'count' => 6, 'quantity' => '3 oz'],
                ]
            ],
            [
                'id' => $ingredientC->id,
                'description' => $ingredientC->description,
                'uses' => [
                    ['meal' => $mealA->name, 'count' => 5, 'quantity' => 'a pound'],
                    ['meal' => $mealC->name, 'count' => 11, 'quantity' => '340ml'],
                    ['meal' => $mealE->name, 'count' => 6, 'quantity' => '3 cup'],
                ]
            ],
            [
                'id' => $ingredientD->id,
                'description' => $ingredientD->description,
                'uses' => [
                    ['meal' => $mealA->name, 'count' => 5, 'quantity' => '400g'],
                    ['meal' => $mealD->name, 'count' => 6, 'quantity' => '22lb'],
                ]
            ],
            [
                'id' => $ingredientE->id,
                'description' => $ingredientE->description,
                'uses' => [
                    ['meal' => $mealB->name, 'count' => 3, 'quantity' => '20g'],
                    ['meal' => $mealD->name, 'count' => 6, 'quantity' => '2kg'],
                ]
            ],
            [
                'id' => $ingredientG->id,
                'description' => $ingredientG->description,
                'uses' => [
                    ['meal' => $mealB->name, 'count' => 3, 'quantity' => 'a pack'],
                    ['meal' => $mealD->name, 'count' => 6, 'quantity' => '1/2 box'],
                    ['meal' => $mealE->name, 'count' => 6, 'quantity' => '4'],
                ]
            ],
            [
                'id' => $ingredientH->id,
                'description' => $ingredientH->description,
                'uses' => [
                    ['meal' => $mealB->name, 'count' => 3, 'quantity' => '260ml'],
                ]
            ],
            [
                'id' => $ingredientI->id,
                'description' => $ingredientI->description,
                'uses' => [
                    ['meal' => $mealC->name, 'count' => 11, 'quantity' => '250g'],
                ]
            ],
            [
                'id' => $ingredientJ->id,
                'description' => $ingredientJ->description,
                'uses' => [
                    ['meal' => $mealC->name, 'count' => 11, 'quantity' => '1 can'],
                    ['meal' => $mealE->name, 'count' => 6, 'quantity' => '6 spoons'],
                ]
            ],
        ];

        $this->assertEquals($exoected, $batch->ingredientList());
    }
}
