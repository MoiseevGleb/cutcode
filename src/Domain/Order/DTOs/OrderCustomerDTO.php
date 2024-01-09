<?php

namespace Domain\Order\DTOs;

use Support\Traits\Makeable;

class OrderCustomerDTO
{
    use Makeable;
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly int $phone,
        public readonly int $city,
        public readonly string $address,
    ) {}

    public function fillName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public static function fromArray(array $array): self
    {
        return self::make(
            $array['first_name'] ?? '',
            $array['last_name'] ?? '',
            $array['email'] ?? '',
            $array['phone'] ?? '',
            $array['city'] ?? '',
            $array['address'] ?? '',
        );
    }

    public function toArray()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'address' => $this->address,
        ];
    }
}
