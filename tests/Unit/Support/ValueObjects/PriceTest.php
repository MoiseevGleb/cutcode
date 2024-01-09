<?php

namespace Support\ValueObjects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class PriceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_success()
    {
        $price = Price::make(100000);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(100000, $price->raw());
        $this->assertEquals(1000, $price->value());
        $this->assertEquals('RUB', $price->currency());
        $this->assertEquals('₽', $price->symbol());
        $this->assertEquals('1 000,00 ₽', $price);
    }

    /**
     * @test
     */
    public function it_throws_invalid_price_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price can not be less than zero');

        Price::make(0);
    }

    /**
     * @test
     */
    public function it_throws_invalid_currency_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No such currency');

        Price::make(1000, 'USD');
    }
}
