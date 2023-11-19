<?php

namespace App\Http\Controllers\Auth;

use Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_login_page_success()
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Войти в аккаунт')
            ->assertViewIs('auth.login');
    }

    /**
     * @test
     */
    public function it_handle_fail()
    {
        $request = [
            'email' => 'notfound@mail.com',
            'password' => str()->random(10),
        ];

        $this->post(action([SignInController::class, 'handle']), $request)
            ->assertInvalid('email');

        $this->assertGuest();
    }

    /**
     * @test
     */
    public function it_handle_success()
    {
        $password = '123123';

        $user = User::factory()->create([
            'email' => 'glebmoiseev@yandex.ru',
            'password' => bcrypt($password),
        ]);

        $request = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function it_logout_success()
    {
        $user = User::factory()->create([
            'email' => 'testing@yandex.ru',
        ]);

        $this->actingAs($user)
            ->delete(action([SignInController::class, 'logout']));

        $this->assertGuest();
    }

    /**
     * @test
     */
    public function it_logout_guest_middleware_fail()
    {
        $this->delete(action([SignInController::class, 'logout']))
            ->assertRedirect(route('login'));
    }
}
