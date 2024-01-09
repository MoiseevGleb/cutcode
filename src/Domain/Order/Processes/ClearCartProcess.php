<?php

namespace Domain\Order\Processes;

use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Models\Order;

class ClearCartProcess implements OrderProcessContract
{
    public function handle(Order $order, $next)
    {
        cart()->clear();

        return $next($order);
    }
}
