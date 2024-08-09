<?php

namespace App\Utils;

class PriceFormatter
{
    /**
     * Converts the given number to PLN currency notation
     *
     * @param int $price
     * @return string
     */
    public static function readable(int $price): string
    {
        return sprintf(
            '%s PLN',
            number_format($price, 0, '', ' ')
        );
    }
}
