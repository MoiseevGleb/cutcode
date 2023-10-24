<?php

namespace Tests\Feature\App\Http\Controllers;


use App\Http\Controllers\AuthController;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailToNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_login_page_success()
    {
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertViewIs('auth.index');
    }

    /**
     * @test
     * @return void
     */
    public function it_store_success(): void
    {
        Notification::fake();
        Event::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'govnobuilov@yandex.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $request['email']
        ]);

        $response = $this->post(
            action([AuthController::class, 'store']),
            $request
        );

        $response->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        $user = User::query()->where('email', $request['email'])->first();

        Event::assertDispatched(Registered::class);

        Event::assertListening(
            Registered::class,
            SendEmailToNewUserListener::class
        );

        $event = new Registered($user);
        (new SendEmailToNewUserListener())->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticated();

        $response->assertRedirect(route('home'));
    }
}
