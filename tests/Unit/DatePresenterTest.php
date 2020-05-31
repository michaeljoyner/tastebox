<?php


namespace Tests\Unit;


use App\DatePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DatePresenterTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function present_a_range_with_same_month()
    {
        $from = Carbon::today()->startOfYear()->startOfMonth();
        $to = Carbon::today()->startOfYear()->startOfMonth()->addWeek();

        $expected = sprintf("%s to %s", $from->format("jS"), $to->format("jS M, Y"));

        $this->assertEquals($expected, DatePresenter::range($from, $to, "to"));
    }

    /**
     *@test
     */
    public function present_a_range_with_same_year_but_different_months()
    {
        $from = Carbon::today()->startOfYear()->endOfMonth()->subDays(3);
        $to = Carbon::parse($from)->addWeek();

        $expected = sprintf("%s to %s", $from->format("jS M"), $to->format("jS M, Y"));

        $this->assertEquals($expected, DatePresenter::range($from, $to, "to"));
    }

    /**
     *@test
     */
    public function present_a_range_with_different_years()
    {
        $from = Carbon::today()->endOfYear()->subDays(3);
        $to = Carbon::parse($from)->addWeek();

        $expected = sprintf("%s to %s", $from->format("jS M, Y"), $to->format("jS M, Y"));

        $this->assertEquals($expected, DatePresenter::range($from, $to, "to"));
    }
}
