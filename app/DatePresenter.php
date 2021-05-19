<?php


namespace App;


use Illuminate\Support\Carbon;

class DatePresenter
{

    const PRETTY_DMY = 'jS M, Y';
    const PRETTY_DMY_DAY = 'D, jS M';
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

    public static function pretty(?Carbon $date): string
    {
        return $date === null ? '' : $date->format(self::PRETTY_DMY);
    }

    public static function prettyWithDay(? Carbon $date): string
    {
        return $date === null ? '' : $date->format(self::PRETTY_DMY_DAY);
    }

    public static function standard(?Carbon $date): string
    {
        return $date === null ? '' : $date->format(self::STANDARD);
    }
}
