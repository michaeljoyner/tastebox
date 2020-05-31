<?php


namespace App;


use Illuminate\Support\Carbon;

class DatePresenter
{

    const PRETTY_DMY = 'jS M, Y';
    const STANDARD = 'Y-m-d';

    public static function range(Carbon $from, Carbon $to, $separator = '-')
    {
        if(($from->year === $to->year) && ($from->month === $to->month)) {
            return sprintf("%s %s %s", $from->format("jS"), $separator, $to->format("jS M, Y"));
        }

        if(($from->year === $to->year) && ($from->month !== $to->month)) {
            return sprintf("%s %s %s", $from->format("jS M"), $separator, $to->format("jS M, Y"));
        }

        return sprintf("%s %s %s", $from->format("jS M, Y"), $separator, $to->format("jS M, Y"));

    }
}
