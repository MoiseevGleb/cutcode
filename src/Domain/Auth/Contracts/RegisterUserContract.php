<?php

namespace Domain\Auth\Contracts;

use Domain\Auth\DTOs\NewUserDTO;

interface RegisterUserContract
{
    public function __invoke(NewUserDTO $DTO);
}
