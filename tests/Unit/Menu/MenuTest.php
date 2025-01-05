<?php


namespace Tests\Unit\Menu;


use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
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
     * @test
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

    /**
     * @test
     */
    public function get_next_to_be_delivered()
    {
        $old = factory(Menu::class)->state('old')->create();
        $current = factory(Menu::class)->state('current')->create();
        $upcoming = factory(Menu::class)->state('upcoming')->create();

        $next_up = Menu::nextUp();

        $this->assertTrue($next_up->is($current));
    }

    /**
     * @test
     */
    public function can_open_a_menu_for_orders()
    {
        $menu = factory(Menu::class)->state('closed')->create();

        $menu->openForOrders();

        $this->assertTrue($menu->fresh()->can_order);
    }

    /**
     * @test
     */
    public function can_close_menu_for_orders()
    {
        $menu = factory(Menu::class)->state('open')->create();

        $menu->closedForOrders();

        $this->assertFalse($menu->fresh()->can_order);
    }

    /**
     * @test
     */
    public function can_scope_to_available_for_orders()
    {
        $open_current = factory(Menu::class)->state('current')->create([
            'can_order'  => true,
            'current_to' => Carbon::tomorrow(),
        ]);
        $open_upcoming = factory(Menu::class)->state('upcoming')->create([
            'can_order' => true,
        ]);
        $open_old = factory(Menu::class)->state('old')->create([
            'can_order' => true,
        ]);
        $closed_current = factory(Menu::class)->state('current')->create([
            'can_order' => false,
        ]);
        $closed_upcoming = factory(Menu::class)->state('upcoming')->create([
            'can_order' => false,
        ]);

        $scoped = Menu::available()->get();

        $this->assertCount(2, $scoped);
        $this->assertTrue($scoped->contains($open_current));
        $this->assertTrue($scoped->contains($open_upcoming));
        $this->assertFalse($scoped->contains($open_old));
        $this->assertFalse($scoped->contains($closed_current));
        $this->assertFalse($scoped->contains($closed_upcoming));
    }



    /**
     * @test
     */
    public function can_get_the_next_menu_for_preparation()
    {
        $this->travelTo(Carbon::parse('next monday'));
        $current_prep = factory(Menu::class)->create([
            'current_from'  => now()->subWeek()->startOfDay(),
            'current_to'    => now()->subWeek()->addDays(3)->endOfDay(),
            'delivery_from' => now()->subWeek()->endOfWeek()->addDays(2),
            'can_order'     => true,
        ]);

        $next_prep = factory(Menu::class)->create([
            'current_from'  => now()->startOfDay(),
            'current_to'    => now()->startOfDay()->addDays(3)->endOfDay(),
            'delivery_from' => now()->startOfDay()->endOfWeek()->addDays(2),
            'can_order'     => true,
        ]);

        $future_prep = factory(Menu::class)->create([
            'current_from'  => now()->addWeek()->startOfDay(),
            'current_to'    => now()->addWeek()->startOfDay()->addDays(3)->endOfDay(),
            'delivery_from' => now()->addWeek()->startOfDay()->endOfWeek()->addDays(2),
            'can_order'     => true,
        ]);

        $next_to_prep = Menu::nextToPrep();

        $this->assertTrue($next_to_prep->is($next_prep));


    }


}
