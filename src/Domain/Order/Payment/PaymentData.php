<?php

namespace Domain\Order\Payment;

use Ramsey\Collection\Collection;
use Support\ValueObjects\Price;

class PaymentData
{
    public function __construct(
        public string $id,
        public string $description,
        public string $returnUrl,
        public Price $amount,
        public Collection $meta,
    ) {}
}
