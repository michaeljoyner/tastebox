<?php


namespace Tests\Unit\Meals;


use App\Meals\Classification;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealClassificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function classifications_can_be_assigned_to_a_meal()
    {
        $veg = factory(Classification::class)->create();
        $kids = factory(Classification::class)->create();

        $meal = factory(Meal::class)->create();

        $meal->assignClassifications([$veg->id, $kids->id]);

        $this->assertCount(2,$meal->classifications);

        $this->assertTrue($meal->classifications->contains($veg));
        $this->assertTrue($meal->classifications->contains($kids));
    }

    /**
     *@test
     */
    public function assigning_classifications_overwrites_any_previous_ones()
    {
        $veg = factory(Classification::class)->create();
        $kids = factory(Classification::class)->create();
        $meat_lovers = factory(Classification::class)->create();

        $meal = factory(Meal::class)->create();

        $meal->assignClassifications([$veg->id, $kids->id]);

        $this->assertCount(2,$meal->classifications);

        $this->assertTrue($meal->classifications->contains($veg));
        $this->assertTrue($meal->classifications->contains($kids));

        $meal->assignClassifications([$meat_lovers->id]);

        $this->assertCount(1,$meal->fresh()->classifications);

        $this->assertTrue($meal->fresh()->classifications->contains($meat_lovers));
    }

    /**
     *@test
     */
    public function can_assign_empty_array()
    {
        $veg = factory(Classification::class)->create();
        $kids = factory(Classification::class)->create();

        $meal = factory(Meal::class)->create();

        $meal->assignClassifications([$veg->id, $kids->id]);

        $this->assertCount(2,$meal->classifications);

        $this->assertTrue($meal->classifications->contains($veg));
        $this->assertTrue($meal->classifications->contains($kids));

        $meal->assignClassifications([]);

        $this->assertCount(0, $meal->fresh()->classifications);
    }
}
