<?php


namespace Tests\Unit\Menu;


use App\DatePresenter;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MenuPresentationTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function presented_as_data_array()
    {
        $current_from = Carbon::today()->addWeek()->startOfWeek();
        $current_to = Carbon::today()->addWeek()->startOfWeek()->addDays(5);
        $delivery_from = Carbon::today()->addWeek()->endOfWeek()->addDay();

        $menu = factory(Menu::class)->create([
            'current_from' => $current_from,
            'current_to' => $current_to,
            'delivery_from' => $delivery_from,
            'can_order' => false,
        ]);

        $expected = [
            'id' => $menu->id,
            'can_order' => false,
            'current_from_date' => $current_from->format(DatePresenter::STANDARD),
            'current_from_pretty' => $current_from->format(DatePresenter::PRETTY_DMY),
            'current_to_date' => $current_to->format(DatePresenter::STANDARD),
            'current_to_pretty' => $current_to->format(DatePresenter::PRETTY_DMY),
            'current_range_pretty' => DatePresenter::range($current_from, $current_to),
            'delivery_from_date' => $delivery_from->format(DatePresenter::STANDARD),
            'delivery_from_pretty' => $delivery_from->format(DatePresenter::PRETTY_DMY),
            'week_number' => $current_from->week,
            'is_current' => false,
            'status' => Menu::UPCOMING,
            'meals' => [],
        ];

        $this->assertEquals($expected, $menu->toArray());


    }
}
