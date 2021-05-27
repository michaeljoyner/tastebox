<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Batch;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ReportBatchTotalsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_batch_report_command_saves_batch_totals_to_db()
    {
        $batch = $this->setUpBatch();

        Artisan::call('batch:report-latest');

        $this->assertDatabaseHas('batch_reports', [
            'menu_id'        => $batch->menuId(),
            'week'           => $batch->week,
            'total_kits'     => $batch->totalKits(),
            'total_meals'     => $batch->totalPackedMeals(),
            'total_servings' => $batch->totalServings(),
        ]);
    }

    private function setUpBatch(): Batch
    {
        $menu = factory(Menu::class)->state('old')->create();

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

        $test_address = new Address([
            'line_one'    => '123 Test rd',
            'line_two'    => 'Fakerton',
            'city'        => 'Testville',
            'postal_code' => '3201',
        ]);

        $orderA->addKit($kitA, $test_address);
        $orderB->addKit($kitB, $test_address);
        $orderC->addKit($kitC, $test_address);

        $batch = $menu->getBatch();

        return $batch;
    }
}
