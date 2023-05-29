<?php


namespace Tests\Unit\Purchases;


use App\DatePresenter;
use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Orders\Menu;
use App\Purchases\BasketPresenter;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PresentShoppingBasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function present_the_basket()
    {
        $menuA = factory(Menu::class)->state('current')->create([
            'current_to' => Carbon::tomorrow(),
            'can_order'  => true,
        ]);
        $menuB = factory(Menu::class)->state('upcoming')->create([
            'can_order' => true
        ]);

        $mealA = factory(Meal::class)->state('budget')->create();
        $mealB = factory(Meal::class)->state('budget')->create();
        $mealC = factory(Meal::class)->state('standard')->create();
        $mealD = factory(Meal::class)->state('standard')->create();
        $mealE = factory(Meal::class)->state('premium')->create();
        $mealF = factory(Meal::class)->state('premium')->create();

        $menuA->setMeals([$mealA->id, $mealB->id, $mealE->id]);
        $menuB->setMeals([$mealC->id, $mealD->id, $mealF->id]);

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA, 2);
        $kitA->setMeal($mealB, 3);
        $kitA->setMeal($mealE, 2);

        $kitB = $basket->addKit($menuB->id);
        $kitB->setMeal($mealC, 4);
        $kitB->setMeal($mealD, 5);
        $kitB->setMeal($mealF, 3);

        $kitA->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HILTON, '123 test street'));
        $kitB->setDeliveryAddress(new DeliveryAddress(DeliveryArea::NOT_SET, ''));

        $expected = [
            'total_boxes'              => 2,
            'total_price'              => 19 * Meal::SERVING_PRICE,
            'kits'                     => [
                [
                    'name'               => 'Box One',
                    'id'                 => $kitA->id,
                    'menu_id'            => $menuA->id,
                    'delivery_date'      => $menuA->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
                    'eligible_for_order' => true,
                    'meals_count'        => 3,
                    'servings_count'     => 7,
                    'price'              => (5 * MealPriceTier::BUDGET->price()) + (2 * MealPriceTier::PREMIUM->price()),
                    'delivery_area'      => $kitA->delivery_address->area->description(),
                    'delivery_address'   => $kitA->delivery_address->address,
                    'deliver_with'       => '',
                    'can_deliver'        => true,
                    'meals'              => [
                        [
                            'id'       => $mealA->id,
                            'name'     => $mealA->name,
                            'thumb'    => $mealA->titleImage('thumb'),
                            'servings' => 2,
                            'price'    => 2 * MealPriceTier::BUDGET->price(),
                        ],
                        [
                            'id'       => $mealB->id,
                            'name'     => $mealB->name,
                            'thumb'    => $mealB->titleImage('thumb'),
                            'servings' => 3,
                            'price'    => 3 * MealPriceTier::BUDGET->price(),
                        ],
                        [
                            'id'       => $mealE->id,
                            'name'     => $mealE->name,
                            'thumb'    => $mealE->titleImage('thumb'),
                            'servings' => 2,
                            'price'    => 2 * MealPriceTier::PREMIUM->price(),
                        ],
                    ]
                ],
                [
                    'name'               => 'Box Two',
                    'id'                 => $kitB->id,
                    'menu_id'            => $menuB->id,
                    'delivery_date'      => $menuB->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
                    'eligible_for_order' => true,
                    'meals_count'        => 3,
                    'servings_count'     => 12,
                    'price'              => (9 * MealPriceTier::STANDARD->price()) + (3 * MealPriceTier::PREMIUM->price()),
                    'delivery_area'      => $kitB->delivery_address->area->description(),
                    'delivery_address'   => $kitB->delivery_address->address,
                    'deliver_with'       => '',
                    'can_deliver'        => false,
                    'meals'              => [
                        [
                            'id'       => $mealC->id,
                            'name'     => $mealC->name,
                            'thumb'    => $mealC->titleImage('thumb'),
                            'servings' => 4,
                            'price'    => 4 * MealPriceTier::STANDARD->price(),
                        ],
                        [
                            'id'       => $mealD->id,
                            'name'     => $mealD->name,
                            'thumb'    => $mealD->titleImage('thumb'),
                            'servings' => 5,
                            'price'    => 5 * MealPriceTier::STANDARD->price(),
                        ],
                        [
                            'id'       => $mealF->id,
                            'name'     => $mealF->name,
                            'thumb'    => $mealF->titleImage('thumb'),
                            'servings' => 3,
                            'price'    => 3 * MealPriceTier::PREMIUM->price(),
                        ],
                    ],

                ],

            ],
            'suggested_addresses'      => [
                [
                    'kit_id'           => $kitA->id,
                    'delivery_area'    => [
                        'key'   => $kitA->delivery_address->area->value,
                        'value' => $kitA->delivery_address->area->description()
                    ],
                    'delivery_address' => $kitA->delivery_address->address,
                ],
            ],
            'available_delivery_areas' => DeliveryArea::activeAreas(),

        ];


        $this->assertEquals($expected, $basket->presentForReview());
    }

    /**
     * @test
     */
    public function basket_presenter_presents_kit_correctly()
    {
        $menuA = factory(Menu::class)->state('current')->create([
            'current_to' => Carbon::tomorrow(),
            'can_order'  => true,
        ]);


        $mealA = factory(Meal::class)->state('budget')->create();
        $mealB = factory(Meal::class)->state('standard')->create();
        $mealC = factory(Meal::class)->state('premium')->create();
        $menuA->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA, 2);
        $kitA->setMeal($mealB, 3);
        $kitA->setMeal($mealC, 2);

        $kitB = $basket->addKit($menuA->id);
        $kitB->setMeal($mealA, 4);
        $kitB->setMeal($mealB, 5);
        $kitB->setMeal($mealC, 3);

        $expected = [
            'name'               => 'Box Two',
            'id'                 => $kitB->id,
            'menu_id'            => $menuA->id,
            'delivery_date'      => $menuA->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
            'eligible_for_order' => true,
            'meals_count'        => 3,
            'servings_count'     => 12,
            'price'              => (4 * MealPriceTier::BUDGET->price()) + (5 * MealPriceTier::STANDARD->price()) + (3 * MealPriceTier::PREMIUM->price()),
            'delivery_area'      => $kitA->delivery_address->area->description(),
            'delivery_address'   => $kitA->delivery_address->address,
            'deliver_with'       => 'Box One',
            'can_deliver'        => false,
            'meals'              => [
                [
                    'id'       => $mealA->id,
                    'name'     => $mealA->name,
                    'thumb'    => $mealA->titleImage('thumb'),
                    'servings' => 4,
                    'price'    => 4 * (MealPriceTier::BUDGET)->price()
                ],
                [
                    'id'       => $mealB->id,
                    'name'     => $mealB->name,
                    'thumb'    => $mealB->titleImage('thumb'),
                    'servings' => 5,
                    'price'    => 5 * (MealPriceTier::STANDARD)->price()
                ],
                [
                    'id'       => $mealC->id,
                    'name'     => $mealC->name,
                    'thumb'    => $mealC->titleImage('thumb'),
                    'servings' => 3,
                    'price'    => 3 * (MealPriceTier::PREMIUM)->price()
                ],
            ],

        ];

        $this->assertSame($expected, (new BasketPresenter($basket))->presentKit($kitB));
    }
}
