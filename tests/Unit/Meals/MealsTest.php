<?php


namespace Tests\Unit\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MealsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function get_meals_with_the_last_used_date()
    {
        $lastUsed = DB::table('meal_menus')->select('meal_menu.meal_id, max(meal_menu.menu_id), max(menus.current_from) as last_used')
                      ->leftJoin('menus', 'menus.id', '=', 'meal_menu.menu.id')
                      ->groupBy('meal_menu.meal_id')->dump();

        dd($lastUsed);
    }

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

        $this->assertSame($meal->ingredients->toArray(), $copy->ingredients->toArray());
    }
}
