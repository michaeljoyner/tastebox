<?php


namespace Tests\Unit\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function meal_can_be_published()
    {
        $meal = factory(Meal::class)->state('private')->create();

        $meal->publish();

        $this->assertTrue($meal->fresh()->is_public);
    }

    /**
     *@test
     */
    public function a_meal_can_be_made_private()
    {
        $meal = factory(Meal::class)->state('public')->create();

        $meal->retract();

        $this->assertFalse($meal->fresh()->is_public);
    }
}
