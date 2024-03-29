<?php

namespace Support\ValueObjects;

use InvalidArgumentException;
use Stringable;
use Support\Traits\Makeable;

class Price implements Stringable
{
    use Makeable;

    private array $currencies = [
        'RUB' => '₽'
    ];

    public function __construct(
        private readonly int    $value,
        private readonly string $currency = 'RUB',
        private readonly int    $precision = 100,
    )
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Price can not be less than zero');
        }

        if (!isset($this->currencies[$currency])){
            throw new InvalidArgumentException('No such currency');
        }
    }

    public function raw(): float|int
    {
        return $this->value;
    }

    public function value(): float|int
    {
        return $this->value / $this->precision;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function symbol(): string
    {
        return $this->currencies[$this->currency];
    }

    public function __toString(): string
    {
        return number_format($this->value(), 2, ',', ' ') . ' ' . $this->symbol();
    }
}
