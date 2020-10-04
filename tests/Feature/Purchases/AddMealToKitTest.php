<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddMealToKitTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_a_meal_with_servings_to_a_kit()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/meals", [
            'meal_id' => $meal->id,
            'servings' => 4,
        ]);
        $response->assertSuccessful();

        $kits = session('basket.kits');
        $current_kit = collect($kits)->first(fn ($k) => $k->id === $kit->id);
        $this->assertCount(1, $current_kit->meals);
        $this->assertEquals($meal->id, $current_kit->meals[0]['id']);
        $this->assertEquals(4, $current_kit->meals[0]['servings']);
    }

    /**
     *@test
     */
    public function the_meal_id_must_exists_in_meals_table()
    {
        $menu = factory(Menu::class)->create();
        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/meals", [
            'meal_id' => 999,
            'servings' => 4,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_id');
    }

    /**
     *@test
     */
    public function the_meal_must_be_available_for_the_kit()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();

        $menu->setMeals([]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/meals", [
            'meal_id' => $meal->id,
            'servings' => 4,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_id');
    }

    /**
     *@test
     */
    public function the_servings_is_required()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);
        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/meals", [
            'meal_id' => $meal->id,
            'servings' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('servings');
    }

    /**
     *@test
     */
    public function the_servings_must_be_an_integer()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);
        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/meals", [
            'meal_id' => $meal->id,
            'servings' => 'not-an-integer',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('servings');
    }

    /**
     *@test
     */
    public function the_servings_must_be_either_one_two_or_four()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);
        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $bad_servings = collect([-1,0,3,5,10]);

        $bad_servings->each(function($serving) use ($kit, $meal) {
            $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/meals", [
                'meal_id' => $meal->id,
                'servings' => $serving,
            ]);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors('servings');
        });


    }
}
