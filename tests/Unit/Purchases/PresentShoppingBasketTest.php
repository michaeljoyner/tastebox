<?php


namespace Tests\Unit\Purchases;


use App\DatePresenter;
use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
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

        $kitA->setDeliveryAddress(new DeliveryAddress(DeliveryArea::HILTON, '123 test street'));
        $kitB->setDeliveryAddress(new DeliveryAddress(DeliveryArea::NOT_SET, ''));

        $expected = [
            'total_boxes'         => 2,
            'total_price'         => 19 * Meal::SERVING_PRICE,
            'kits'                => [
                [
                    'name'               => 'Box One',
                    'id'                 => $kitA->id,
                    'menu_id'            => $menuA->id,
                    'delivery_date'      => $menuA->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
                    'eligible_for_order' => true,
                    'meals_count'        => 3,
                    'servings_count'     => 7,
                    'price'              => 7 * Meal::SERVING_PRICE,
                    'delivery_area'      => $kitA->delivery_address->area->name,
                    'delivery_address'   => $kitA->delivery_address->address,
                    'deliver_with'       => '',
                    'can_deliver'        => true,
                    'meals'              => [
                        [
                            'id'       => $mealA->id,
                            'name'     => $mealA->name,
                            'thumb'    => $mealA->titleImage('thumb'),
                            'servings' => 2,
                        ],
                        [
                            'id'       => $mealB->id,
                            'name'     => $mealB->name,
                            'thumb'    => $mealB->titleImage('thumb'),
                            'servings' => 3,
                        ],
                        [
                            'id'       => $mealE->id,
                            'name'     => $mealE->name,
                            'thumb'    => $mealE->titleImage('thumb'),
                            'servings' => 2,
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
                    'price'              => 12 * Meal::SERVING_PRICE,
                    'delivery_area'      => $kitB->delivery_address->area->name,
                    'delivery_address'   => $kitB->delivery_address->address,
                    'deliver_with'       => '',
                    'can_deliver'        => false,
                    'meals'              => [
                        [
                            'id'       => $mealC->id,
                            'name'     => $mealC->name,
                            'thumb'    => $mealC->titleImage('thumb'),
                            'servings' => 4,
                        ],
                        [
                            'id'       => $mealD->id,
                            'name'     => $mealD->name,
                            'thumb'    => $mealD->titleImage('thumb'),
                            'servings' => 5,
                        ],
                        [
                            'id'       => $mealF->id,
                            'name'     => $mealF->name,
                            'thumb'    => $mealF->titleImage('thumb'),
                            'servings' => 3,
                        ],
                    ],

                ],

            ],
            'suggested_addresses' => [
                [
                    'kit_id'           => $kitA->id,
                    'delivery_area'    => [
                        'key'   => $kitA->delivery_address->area->value,
                        'value' => $kitA->delivery_address->area->name
                    ],
                    'delivery_address' => $kitA->delivery_address->address,
                ],
            ],

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


        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $menuA->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);
        $kitA->setMeal($mealC->id, 2);

        $kitB = $basket->addKit($menuA->id);
        $kitB->setMeal($mealA->id, 4);
        $kitB->setMeal($mealB->id, 5);
        $kitB->setMeal($mealC->id, 3);

        $expected = [
            'name'               => 'Box Two',
            'id'                 => $kitB->id,
            'menu_id'            => $menuA->id,
            'delivery_date'      => $menuA->delivery_from->format(DatePresenter::PRETTY_DMY_DAY),
            'eligible_for_order' => true,
            'meals_count'        => 3,
            'servings_count'     => 12,
            'price'              => 12 * Meal::SERVING_PRICE,
            'delivery_area'      => $kitA->delivery_address->area->name,
            'delivery_address'   => $kitA->delivery_address->address,
            'deliver_with'       => 'Box One',
            'can_deliver'        => false,
            'meals'              => [
                [
                    'id'       => $mealA->id,
                    'name'     => $mealA->name,
                    'thumb'    => $mealA->titleImage('thumb'),
                    'servings' => 4,
                ],
                [
                    'id'       => $mealB->id,
                    'name'     => $mealB->name,
                    'thumb'    => $mealB->titleImage('thumb'),
                    'servings' => 5,
                ],
                [
                    'id'       => $mealC->id,
                    'name'     => $mealC->name,
                    'thumb'    => $mealC->titleImage('thumb'),
                    'servings' => 3,
                ],
            ]
        ];

        $this->assertSame($expected, (new BasketPresenter($basket))->presentKit($kitB));
    }
}
