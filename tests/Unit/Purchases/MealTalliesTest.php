<?php


namespace Tests\Unit\Purchases;


use App\DatePresenter;
use App\Meals\Meal;
use App\Orders\Batch;
use App\Orders\BatchMealsTally;
use App\Orders\MealTally;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealTalliesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_new_tallies_from_batch_meal_tallies()
    {
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();
        $mealF = factory(Meal::class)->create();
        $meals = collect([
            $mealA,
            $mealB,
            $mealC,
            $mealD,
            $mealE,
            $mealF,
        ]);
        $batch = $this->getBatch($meals);
        $batch_tallies = BatchMealsTally::fromBatch($batch);

        MealTally::forBatch($batch_tallies);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealA->id,
            'times_offered' => 1,
            'total_ordered' => 2,
            'total_servings' => 5,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealB->id,
            'times_offered' => 1,
            'total_ordered' => 1,
            'total_servings' => 3,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealC->id,
            'times_offered' => 1,
            'total_ordered' => 3,
            'total_servings' => 11,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealF->id,
            'times_offered' => 1,
            'total_ordered' => 0,
            'total_servings' => 0,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);
    }

    /**
     *@test
     */
    public function tallies_for_repeated_meals_add_to_rows_instead_of_new_row()
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

        factory(MealTally::class)->create([
            'meal_id' => $mealA->id,
            'times_offered' => 3,
            'total_ordered' => 15,
            'total_servings' => 68,
            'last_offered' => now()->subMonth(),
        ]);

        factory(MealTally::class)->create([
            'meal_id' => $mealB->id,
            'times_offered' => 1,
            'total_ordered' => 11,
            'total_servings' => 45,
            'last_offered' => now()->subWeek(),
        ]);

        $batch = $this->getBatch($meals);
        $batch_tallies = BatchMealsTally::fromBatch($batch);

        MealTally::forBatch($batch_tallies);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealA->id,
            'times_offered' => 4,
            'total_ordered' => 2 + 15,
            'total_servings' => 5 + 68,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealB->id,
            'times_offered' => 1 + 1,
            'total_ordered' => 1 + 11,
            'total_servings' => 3 + 45,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);

        $this->assertDatabaseHas('meal_tallies', [
            'meal_id' => $mealC->id,
            'times_offered' => 1,
            'total_ordered' => 3,
            'total_servings' => 11,
            'last_offered' => $batch->delivery_date->format(DatePresenter::STANDARD),
        ]);

        $this->assertCount(1, MealTally::where('meal_id', $mealA->id)->get());
        $this->assertCount(1, MealTally::where('meal_id', $mealB->id)->get());

    }

    /**
     *@test
     */
    public function can_query_meals_with_tallies()
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

        factory(MealTally::class)->create([
            'meal_id' => $mealA->id,
            'times_offered' => 3,
            'total_ordered' => 15,
            'total_servings' => 68,
            'last_offered' => now()->subMonth(),
        ]);

        factory(MealTally::class)->create([
            'meal_id' => $mealB->id,
            'times_offered' => 1,
            'total_ordered' => 11,
            'total_servings' => 45,
            'last_offered' => now()->subWeek(),
        ]);

        $batch = $this->getBatch($meals);
        $batch_tallies = BatchMealsTally::fromBatch($batch);

        MealTally::forBatch($batch_tallies);

        $this->assertSame(4, $mealA->tallies->times_offered);
        $this->assertSame(17, $mealA->tallies->total_ordered);
        $this->assertSame(73, $mealA->tallies->total_servings);
        $this->assertNotNull(4, $mealA->tallies->times_offered);
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
