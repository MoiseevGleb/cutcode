<?php

namespace Domain\Order\States;

class CanceledOrderState extends OrderState
{
    protected array $allowedTransitions = [];

    public function canBeChanged(): bool
    {
        return false;
    }

    public function value(): string
    {
        return 'canceled';
    }

    public function humanValue(): string
    {
        return 'Отменён';
    }
}
