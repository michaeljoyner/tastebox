<?php


namespace Tests\Unit\Purchases;


use App\DatePresenter;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PresentShoppingBasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function present_the_basket()
    {
        $menuA = factory(Menu::class)->state('current')->create([
            'current_to' => Carbon::tomorrow(),
            'can_order' => true,
        ]);
        $menuB = factory(Menu::class)->state('upcoming')->create([
            'can_order' => true
        ]);

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();
        $mealF = factory(Meal::class)->create();

        $menuA->setMeals([$mealA->id, $mealB->id, $mealE->id]);
        $menuB->setMeals([$mealC->id, $mealD->id, $mealF->id]);

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);
        $kitA->setMeal($mealE->id, 2);

        $kitB = $basket->addKit($menuB->id);
        $kitB->setMeal($mealC->id, 4);
        $kitB->setMeal($mealD->id, 5);
        $kitB->setMeal($mealF->id, 3);

        $expected = [
            'total_boxes' => 2,
            'total_price' => 19 * Meal::SERVING_PRICE,
            'kits' => [
                [
                    'name' => 'Box One',
                    'id' => $kitA->id,
                    'menu_id' => $menuA->id,
                    'delivery_date' => $menuA->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
                    'eligible_for_order' => true,
                    'meals_count' => 3,
                    'servings_count' => 7,
                    'price' => 7 * Meal::SERVING_PRICE,
                    'meals' => [
                        [
                            'id' => $mealA->id,
                            'name' => $mealA->name,
                            'thumb' => $mealA->titleImage('thumb'),
                            'servings' => 2,
                        ],
                        [
                            'id' => $mealB->id,
                            'name' => $mealB->name,
                            'thumb' => $mealB->titleImage('thumb'),
                            'servings' => 3,
                        ],
                        [
                            'id' => $mealE->id,
                            'name' => $mealE->name,
                            'thumb' => $mealE->titleImage('thumb'),
                            'servings' => 2,
                        ],
                    ]
                ],
                [
                    'name' => 'Box Two',
                    'id' => $kitB->id,
                    'menu_id' => $menuB->id,
                    'delivery_date' => $menuB->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
                    'eligible_for_order' => true,
                    'meals_count' => 3,
                    'servings_count' => 12,
                    'price' => 12 * Meal::SERVING_PRICE,
                    'meals' => [
                        [
                            'id' => $mealC->id,
                            'name' => $mealC->name,
                            'thumb' => $mealC->titleImage('thumb'),
                            'servings' => 4,
                        ],
                        [
                            'id' => $mealD->id,
                            'name' => $mealD->name,
                            'thumb' => $mealD->titleImage('thumb'),
                            'servings' => 5,
                        ],
                        [
                            'id' => $mealF->id,
                            'name' => $mealF->name,
                            'thumb' => $mealF->titleImage('thumb'),
                            'servings' => 3,
                        ],
                    ]
                ],
            ],

        ];


        $this->assertEquals($expected, $basket->presentForReview());
    }
}
