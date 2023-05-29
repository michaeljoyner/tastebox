<?php


namespace Tests\Feature\Menu;


use App\Meals\Ingredient;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchShoppingListTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function can_generate_a_shopping_list_from_a_batch()
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
            $ingredientB->id => ['quantity' => '.5 cup', 'in_kit' => true],
            $ingredientC->id => ['quantity' => '1 pound', 'in_kit' => true],
            $ingredientD->id => ['quantity' => '400 g', 'in_kit' => true],
        ]);

        $mealB->ingredients()->sync([
            $ingredientE->id => ['quantity' => '20g', 'in_kit' => true],
            $ingredientF->id => ['quantity' => '.5 tsp', 'in_kit' => false],
            $ingredientG->id => ['quantity' => '1 pack', 'in_kit' => true],
            $ingredientH->id => ['quantity' => '260ml', 'in_kit' => true],
        ]);

        $mealC->ingredients()->sync([
            $ingredientI->id => ['quantity' => '250g', 'in_kit' => true],
            $ingredientJ->id => ['quantity' => '1 can', 'in_kit' => true],
            $ingredientA->id => ['quantity' => '1 bag', 'in_kit' => true],
            $ingredientC->id => ['quantity' => '340ml', 'in_kit' => true],
        ]);

        $mealD->ingredients()->sync([
            $ingredientE->id => ['quantity' => '2kg', 'in_kit' => true],
            $ingredientG->id => ['quantity' => '.5 box', 'in_kit' => true],
            $ingredientI->id => ['quantity' => '1 pinch', 'in_kit' => false],
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
        $kitA->setMeal($mealA, 2);
        $kitA->setMeal($mealB, 3);
        $kitA->setMeal($mealC, 4);

        $kitB = $basket->addKit($menu->id);
        $kitB->setMeal($mealC, 5);
        $kitB->setMeal($mealD, 6);
        $kitB->setMeal($mealE, 3);

        $kitC = $basket->addKit($menu->id);
        $kitC->setMeal($mealA, 3);
        $kitC->setMeal($mealC, 2);
        $kitC->setMeal($mealE, 3);

        $orderA->addKit($kitA);
        $orderB->addKit($kitB);
        $orderC->addKit($kitC);

        $batch = $menu->getBatch();

        $exoected = [
            [
                'id' => $ingredientA->id,
                'item_name' => $ingredientA->description,
                'amounts' => [
                    'x_unit' => 5.0,
                    'bag' => .25 * 11,
                ],
                'uses' => [
                    "5 servings of {$mealA->name} (4)",
                    "11 servings of {$mealC->name} (1 bag)",
                ],
            ],
            [
                'id' => $ingredientB->id,
                'item_name' => $ingredientB->description,
                'amounts' => [
                    'cup' => 2.5 / 4,
                    'oz' => 18 / 4,
                ],
                'uses' => [
                    "5 servings of {$mealA->name} (.5 cup)",
                    "6 servings of {$mealE->name} (3 oz)",
                ],
            ],
            [
                'id' => $ingredientC->id,
                'item_name' => $ingredientC->description,
                'amounts' => [
                    'pound' => 5 / 4,
                    'ml' => 11 * 340 / 4,
                    'cup' => 18 / 4,
                ],
                'uses' => [
                    "5 servings of {$mealA->name} (1 pound)",
                    "11 servings of {$mealC->name} (340ml)",
                    "6 servings of {$mealE->name} (3 cup)",
                ],
            ],
            [
                'id' => $ingredientD->id,
                'item_name' => $ingredientD->description,
                'amounts' => [
                    'g' => 2000 / 4,
                    'lb' => 22 * 6 / 4,
                ],
                'uses' => [
                    "5 servings of {$mealA->name} (400 g)",
                    "6 servings of {$mealD->name} (22lb)",
                ],
            ],
            [
                'id' => $ingredientE->id,
                'item_name' => $ingredientE->description,
                'amounts' => [
                    'g' => 60 / 4,
                    'kg' => 12 / 4
                ],
                'uses' => [
                    "3 servings of {$mealB->name} (20g)",
                    "6 servings of {$mealD->name} (2kg)",
                ],
            ],
            [
                'id' => $ingredientG->id,
                'item_name' => $ingredientG->description,
                'amounts' => [
                    'pack' => 3 / 4,
                    'box' => 3 / 4,
                    'x_unit' => 24 / 4,
                ],
                'uses' => [
                    "3 servings of {$mealB->name} (1 pack)",
                    "6 servings of {$mealD->name} (.5 box)",
                    "6 servings of {$mealE->name} (4)",
                ],
            ],
            [
                'id' => $ingredientH->id,
                'item_name' => $ingredientH->description,
                'amounts' => [
                    'ml' => 260 * 3 / 4,
                ],
                'uses' => [
                    "3 servings of {$mealB->name} (260ml)",
                ],
            ],
            [
                'id' => $ingredientI->id,
                'item_name' => $ingredientI->description,
                'amounts' => [
                    'g' => 250 * 11 / 4,
                ],
                'uses' => [
                    "11 servings of {$mealC->name} (250g)",
                ],
            ],
            [
                'id' => $ingredientJ->id,
                'item_name' => $ingredientJ->description,
                'amounts' => [
                    'can' => 11 / 4,
                    'spoons' => 36 / 4,
                ],
                'uses' => [
                    "11 servings of {$mealC->name} (1 can)",
                    "6 servings of {$mealE->name} (6 spoons)",
                ],
            ],
        ];

        $this->assertEquals($exoected, $batch->shoppingList());
    }
}
