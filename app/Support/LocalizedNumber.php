<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

class LocalizedNumber
{
    public static function format(int|float $value, int $decimals = 0): string
    {
        $number = number_format($value, $decimals);

        return App::isLocale('bn')
            ? strtr($number, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯'])
            : $number;
    }
}
