<?php

namespace Domain\Order\Actions;

use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Order\DTOs\OrderCustomerDTO;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\Order;

class NewOrderAction
{
    public function __invoke(OrderDTO $order, OrderCustomerDTO $customer, bool $createAccount): Order
    {
        $registerAction = app(RegisterUserContract::class);

        if ($createAccount){
            $registerAction(NewUserDTO::make(
                $customer->fullName(),
                $customer->email,
                $customer->password,
            ));
        }

        return Order::query()->create([
            'payment_method_id' => $order->payment_method_id,
            'delivery_type_id' => $order->delivery_type_id,
        ]);
    }
}
