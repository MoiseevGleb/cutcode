<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterUserAction implements RegisterUserContract
{
    public function __invoke(NewUserDTO $DTO): User
    {
        $user = User::query()->create([
            'name' => $DTO->name,
            'email' => $DTO->email,
            'password' => bcrypt($DTO->password),
        ]);

        event(new Registered($user));

        return $user;
    }
}
