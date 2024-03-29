<?php


namespace Tests\Unit\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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

    /**
     *@test
     */
    public function can_clone_a_meal()
    {
        $meal = factory(Meal::class)->create();
        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $meal->ingredients()->sync([
            $ingredientA->id => ['in_kit' => true, 'quantity' => 'one'],
            $ingredientB->id => ['in_kit' => true, 'quantity' => 'two'],
            $ingredientC->id => ['in_kit' => true, 'quantity' => 'three'],
        ]);

        $copy = Meal::copy($meal, 'test clone meal');

        $this->assertSame('test clone meal', $copy->name);
        $this->assertNotSame($meal->unique_id, $copy->unique_id);
        $this->assertFalse($copy->is_public);
        $this->assertSame($meal->description, $copy->description);
        $this->assertSame($meal->allergens, $copy->allergens);
        $this->assertSame($meal->prep_time, $copy->prep_time);
        $this->assertSame($meal->cook_time, $copy->cook_time);
        $this->assertSame($meal->instructions, $copy->instructions);
        $this->assertSame($meal->serving_energy, $copy->serving_energy);
        $this->assertSame($meal->serving_carbs, $copy->serving_carbs);
        $this->assertSame($meal->serving_fat, $copy->serving_fat);
        $this->assertSame($meal->serving_protein, $copy->serving_protein);


        collect($meal->ingredients->toArray())
            ->each(fn ($ingredient) => $this->assertTrue(
                $copy->ingredients->contains(
                    fn($i) => $i->id === $ingredient['id']
                    && $i->pivot->quantity === $ingredient['quantity']
                    && $i->pivot->bundled === $ingredient['bundled']
                    && $i->pivot->form === $ingredient['form']
                )
            ));
    }

    /**
     *@test
     */
    public function each_meal_has_a_price_tier()
    {
        $meal = factory(Meal::class)->create();

        $this->assertSame(MealPriceTier::STANDARD, $meal->price_tier);

    }
}
