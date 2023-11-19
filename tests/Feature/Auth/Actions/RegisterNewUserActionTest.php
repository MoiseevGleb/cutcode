<?php

namespace Auth\Actions;

use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_success_user_created()
    {
        $this->assertDatabaseMissing('users', ['email' => 'testing@test.com']);

        $action = app(RegisterUserContract::class);

        $action(NewUserDTO::make('Test', 'testing@test.com', '1234567890'));

        $this->assertDatabaseHas('users', ['email' => 'testing@test.com']);
    }

    /**
     * @test
     */
    public function it_should_fail_to_create_user_because_he_already_exists()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $user = User::factory()->create(['email' => 'testing@test.com']);

        $this->assertDatabaseHas('users', ['email' => $user->email]);

        $action = app(RegisterUserContract::class);

        $action(NewUserDTO::make('Test', $user->email, '1234567890'));
    }
}
