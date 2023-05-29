<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\BatchMealsTally;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchMealTalliesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function get_tallies_from_a_batch()
    {
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();
        $meals = collect([
            $mealA,
            $mealB,
            $mealC,
            $mealD,
            $mealE,
        ]);
        $batch = $this->getBatch($meals);

        $tallies = BatchMealsTally::fromBatch($batch);
        $this->assertTrue($tallies->date->isSameDay($batch->delivery_date));

        $mealA_tally = $tallies->forMeal($mealA->id);
        $this->assertSame(2, $mealA_tally['times_ordered']);
        $this->assertSame(5, $mealA_tally['servings_ordered']);

        $mealB_tally = $tallies->forMeal($mealB->id);
        $this->assertSame(1, $mealB_tally['times_ordered']);
        $this->assertSame(3, $mealB_tally['servings_ordered']);

        $mealC_tally = $tallies->forMeal($mealC->id);
        $this->assertSame(3, $mealC_tally['times_ordered']);
        $this->assertSame(11, $mealC_tally['servings_ordered']);

        $mealD_tally = $tallies->forMeal($mealD->id);
        $this->assertSame(1, $mealD_tally['times_ordered']);
        $this->assertSame(6, $mealD_tally['servings_ordered']);

        $mealE_tally = $tallies->forMeal($mealE->id);
        $this->assertSame(2, $mealE_tally['times_ordered']);
        $this->assertSame(6, $mealE_tally['servings_ordered']);


    }

    private function getBatch($meals)
    {
        $menu = factory(Menu::class)->state('old')->create();



        $menu->setMeals($meals->pluck('id')->all());

        $orderA = factory(Order::class)->state('paid')->create();
        $orderB = factory(Order::class)->state('paid')->create();
        $orderC = factory(Order::class)->state('paid')->create();

        $basket = ShoppingBasket::for(null);
        $kitA = $basket->addKit($menu->id);
        $kitA->setMeal($meals[0], 2);
        $kitA->setMeal($meals[1], 3);
        $kitA->setMeal($meals[2], 4);

        $kitB = $basket->addKit($menu->id);
        $kitB->setMeal($meals[2], 5);
        $kitB->setMeal($meals[3], 6);
        $kitB->setMeal($meals[4], 3);

        $kitC = $basket->addKit($menu->id);
        $kitC->setMeal($meals[0], 3);
        $kitC->setMeal($meals[2], 2);
        $kitC->setMeal($meals[4], 3);



        $orderA->addKit($kitA);
        $orderB->addKit($kitB);
        $orderC->addKit($kitC);

        $batch = $menu->getBatch();

        return $batch;
    }
}
