<?php

namespace App\Http\Controllers\Auth;

use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;

class SocialAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private function mockSocialiteCallback(string $githubId): MockInterface
    {
        $user = $this->mock(SocialiteUser::class, function (MockInterface $mock) use ($githubId) {
            $mock->shouldReceive('getId')
                ->once()
                ->andReturn($githubId);

            $mock->shouldReceive('getName')
                ->once()
                ->andReturn(str()->random(10));

            $mock->shouldReceive('getEmail')
                ->once()
                ->andReturn('test@testing.com');
        });

        Socialite::shouldReceive('driver->user')
            ->once()
            ->andReturn($user);

        return $user;
    }

    private function callbackRequest(): TestResponse
    {
        return $this->get(
            action(
                [SocialAuthController::class, 'callback'],
                ['driver' => 'github']
            )
        );
    }

    /**
     * @test
     */
    public function it_driver_not_found_exception()
    {
        $this->expectException(DomainException::class);

        $this->withoutExceptionHandling()
            ->get(
                action(
                    [SocialAuthController::class, 'redirect'],
                    ['driver' => 'vk']
                )
            );

        $this->withoutExceptionHandling()
            ->get(
                action(
                    [SocialAuthController::class, 'callback'],
                    ['driver' => 'vk']
                )
            );
    }

    /**
     * @test
     */
    public function it_authenticated_by_existing_user()
    {
        $githubId = str()->random(10);

        User::factory()->create([
            'github_id' => $githubId,
        ]);

        $this->assertDatabaseHas('users', [
            'github_id' => $githubId,
        ]);

        $this->mockSocialiteCallback($githubId);

        $this->callbackRequest()
            ->assertRedirect(route('home'));

        $this->assertAuthenticated();
    }

    /**
     * @test
     */
    public function it_github_redirect_success(): void
    {
        $this->get(
            action(
                [SocialAuthController::class, 'redirect'],
                ['driver' => 'github']
            )
        )->assertRedirectContains('github.com');
    }

    /**
     * @test
     */
    public function it_github_callback_created_user_success()
    {
        $githubId = str()->random(10);

        $this->assertDatabaseMissing('users', ['github_id' => $githubId]);

        $this->mockSocialiteCallback($githubId);

        $this->callbackRequest()->assertRedirect(route('home'));

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', ['github_id' => $githubId]);
    }
}
