<?php


namespace Tests\Unit\Menu;


use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_meals_for_a_menu()
    {
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id]);
        $menu = $menu->fresh();
        $this->assertCount(2, $menu->meals);
        $this->assertTrue($menu->meals->contains($mealA));
        $this->assertTrue($menu->meals->contains($mealB));
    }

    /**
     *@test
     */
    public function scope_upcoming_menus()
    {
        $old = factory(Menu::class)->state('old')->create();
        $current = factory(Menu::class)->state('current')->create();
        $upcoming = factory(Menu::class)->state('upcoming')->create();

        $scoped = Menu::upcoming()->get();

        $this->assertTrue($scoped->contains($current));
        $this->assertTrue($scoped->contains($upcoming));
        $this->assertFalse($scoped->contains($old));
    }
}
