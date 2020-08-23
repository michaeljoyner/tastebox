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

        $menuA->setMeals([$mealA->id, $mealB->id]);
        $menuB->setMeals([$mealC->id, $mealD->id]);

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);

        $kitB = $basket->addKit($menuB->id);
        $kitB->setMeal($mealC->id, 4);
        $kitB->setMeal($mealD->id, 5);

        $expected = [
            'total_boxes' => 2,
            'total_price' => 910,
            'kits' => [
                [
                    'name' => 'Box One',
                    'id' => $kitA->id,
                    'menu_id' => $menuA->id,
                    'delivery_date' => $menuA->delivery_from->format(DatePresenter::PRETTY_DMY),
                    'meals_count' => 2,
                    'servings_count' => 5,
                    'price' => 325,
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
                    ]
                ],
                [
                    'name' => 'Box Two',
                    'id' => $kitB->id,
                    'menu_id' => $menuB->id,
                    'delivery_date' => $menuB->delivery_from->format(DatePresenter::PRETTY_DMY),
                    'meals_count' => 2,
                    'servings_count' => 9,
                    'price' => 585,
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
                    ]
                ],
            ],

        ];


        $this->assertEquals($expected, $basket->presentForReview());
    }
}
