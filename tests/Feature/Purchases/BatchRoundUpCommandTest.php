<?php


namespace Tests\Feature\Purchases;


use App\Mail\BatchRoundupSummary;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;
use Tests\TestCase;

class BatchRoundUpCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function sends_email_with_batch_roundup_and_shopping_list()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        $this->fakeBrowsershotPdf();

        $batch = $this->makeBatch();

        Artisan::call('batch:round-up');


        Mail::assertQueued(BatchRoundupSummary::class, function($mail) use ($batch){
            $this->assertEquals($mail->batch->name(), $batch->name());
            return true;
        });

    }

    private function makeBatch()
    {
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

        return $menu->getBatch();
    }
}
