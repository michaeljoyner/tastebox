<?php


namespace App;


use Illuminate\Support\Str;

class PhoneNumber
{
    public static function from(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);

        if(Str::startsWith($number, '0')) {
            $number = Str::after($number, '0');
        }

        if(Str::startsWith($number, '27') && (strlen($number) > 10)) {
            $number = Str::after($number, '27');
        }
        return "27" . $number;
    }
}
