<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\Batch;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuBatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function get_batch_for_a_given_menu()
    {
        $this->fakeBrowsershotPdf();
        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();

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

        $orderA->addKit($kitA);
        $orderB->addKit($kitB);
        $orderC->addKit($kitC);

        $batch = $menu->getBatch();
        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertCount(3, $batch->kits);

        $expected_meal_list = [
            [
                'id' => $mealA->id,
                'name' => $mealA->name,
                'servings' => [
                    ['size' => 2, 'count' => 1],
                    ['size' => 3, 'count' => 1],
                ],
                'total_servings' => 5,
            ],
            [
                'id' => $mealB->id,
                'name' => $mealB->name,
                'servings' => [
                    ['size' => 3, 'count' => 1],
                ],
                'total_servings' => 3,
            ],
            [
                'id' => $mealC->id,
                'name' => $mealC->name,
                'servings' => [
                    ['size' => 4, 'count' => 1],
                    ['size' => 5, 'count' => 1],
                    ['size' => 2, 'count' => 1],
                ],
                'total_servings' => 11,
            ],
            [
                'id' => $mealD->id,
                'name' => $mealD->name,
                'servings' => [
                    ['size' => 6, 'count' => 1],
                ],
                'total_servings' => 6,
            ],
            [
                'id' => $mealE->id,
                'name' => $mealE->name,
                'servings' => [
                    ['size' => 3, 'count' => 2],
                ],
                'total_servings' => 6,
            ],
        ];

        $this->assertEquals($expected_meal_list, $batch->mealList());

        $this->assertSame($menu->id, $batch->menuId());
        $this->assertEquals($menu->delivery_from, $batch->deliveryDate());
        $this->assertSame(3, $batch->totalKits());
        $this->assertSame(9, $batch->totalPackedMeals());
        $this->assertSame(31, $batch->totalServings());

    }

    /**
     *@test
     */
    public function can_create_shopping_list_pdf()
    {
        Storage::fake('admin_stuff');
        Storage::disk('admin_stuff')->makeDirectory('shopping-lists');
        $this->fakeBrowsershotPdf();

        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();

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

        $orderA->addKit($kitA);
        $orderB->addKit($kitB);
        $orderC->addKit($kitC);

        $batch = $menu->getBatch();

        $file = $batch->createShoppingListPdf();

        Storage::disk('admin_stuff')->assertExists($file);
    }
}
