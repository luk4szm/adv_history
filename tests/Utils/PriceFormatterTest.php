<?php

namespace App\Tests\Utils;

use App\Utils\PriceFormatter;
use PHPUnit\Framework\TestCase;

class PriceFormatterTest extends TestCase
{
    public function testPriceFormat(): void
    {
        $this->assertSame('1 PLN', PriceFormatter::readable(1));
        $this->assertSame('100 PLN', PriceFormatter::readable(100));
        $this->assertSame('1 100 PLN', PriceFormatter::readable(1100));
        $this->assertSame('1 950 100 PLN', PriceFormatter::readable(1950100));
    }
}
