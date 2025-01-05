<?php

namespace Tests\Unit\Menu;

use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GenerateMenusTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function generate_weekly_menus()
    {
        $this->assertCount(0, Menu::all());

        Menu::generateWeekly(5);

        $this->assertCount(5, Menu::all());

        $menus = Menu::orderBy('current_from')->get();

        $menus->each(function($menu, $index) {
            $this->assertTrue(Carbon::today()->addWeeks($index + 1)->startOfWeek()->isSameDay($menu->current_from));
            $this->assertTrue(Carbon::today()->addWeeks($index + 1)->startOfWeek()->addDays(4)->setTimeFromTimeString('12:00:00')->isSameDay($menu->current_to));
            $this->assertSame(Carbon::FRIDAY, $menu->current_to->dayOfWeek);
            $this->assertTrue(Carbon::today()->addWeeks($index + 1)->endOfWeek()->addDays(1)->isSameDay($menu->delivery_from));
        });

    }

    /**
     *@test
     */
    public function generated_menus_will_be_started_from_after_most_recent()
    {
        Menu::generateWeekly(2);

        $most_recent = Menu::latest('current_from')->first();

        Menu::generateWeekly(1);

        $newest = Menu::latest('current_from')->first();

        $this->assertTrue($most_recent->current_from->addWeek()->isSameDay($newest->current_from));
        $expected = $most_recent->weekOfYear() === 52 ? 1 : $most_recent->weekOfYear() + 1;
        $this->assertEquals($expected, $newest->weekOfYear());
    }
}
