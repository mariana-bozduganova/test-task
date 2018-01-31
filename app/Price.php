<?php

namespace App;


class Price {

    public static function displayFormat($price)
    {
        return '$' . number_format($price / 100, 2, '.', ' ');
    }

} 