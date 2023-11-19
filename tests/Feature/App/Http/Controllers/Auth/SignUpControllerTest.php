<?php

namespace App\Http\Controllers\Auth;

use App\Listeners\SendEmailToNewUserListener;
use App\Notifications\NewUserNotification;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = [
            'name' => 'Глеб',
            'email' => 'govnobuilov@yandex.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];
    }

    private function request(): TestResponse
    {
        return $this->post(
            action([SignUpController::class, 'handle']),
            $this->request
        );
    }

    private function findUser(): User|Builder
    {
        return User::query()
            ->where('email', $this->request['email'])
            ->first();
    }

    /**
     * @test
     */
    public function it_registered_event_and_listeners_dispatched(): void
    {
        Event::fake();

        $this->request();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailToNewUserListener::class);
    }

    /**
     * @test
     */
    public function it_notification_sent(): void
    {
        $this->request();

        Notification::assertSentTo($this->findUser(), NewUserNotification::class);
    }

    /**
     * @test
     */
    public function it_validation_succeeded()
    {
        $this->request()
            ->assertValid();
    }

    /**
     * @test
     */
    public function it_should_fail_validation_on_password_confirm(): void
    {
        $this->request['password'] = '123';
        $this->request['password_confirmation'] = '1234';

        $this->request()
            ->assertInvalid(['password']);
    }

    /**
     * @test
     */
    public function it_user_created_success()
    {
        $this->assertDatabaseMissing('users', ['email' => $this->request['email']]);

        $this->request();

        $this->assertDatabaseHas('users', ['email' => $this->request['email']]);
    }

    /**
     * @test
     */
    public function it_should_fail_validation_on_unique_email()
    {
        User::factory()->create([
            'email' => $this->request['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);

        $this->request()
            ->assertInvalid(['email']);
    }

    /**
     * @test
     */
    public function it_user_authenticated_after_and_redirected()
    {
        $this->request()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->findUser());
    }

    /**
     * @test
     */
    public function it_sign_up_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }
}
