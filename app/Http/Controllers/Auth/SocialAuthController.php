<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Support\SessionRegenarator;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable $exception) {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }
    }

    public function callback(string $driver): RedirectResponse
    {
        if ($driver !== 'github') {
            throw new DomainException("Драйвер \"$driver\" не поддерживается");
        }

        $socialiteUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            "{$driver}_id" => $socialiteUser->getId(),
        ], [
            'name' => $socialiteUser->getName() ?? $socialiteUser->getNickname(),
            'email' => $socialiteUser->getEmail(),
            'password' => bcrypt(str()->random(20)),
        ]);

        SessionRegenarator::run(fn() => auth()->login($user));

        return redirect()->intended(route('home'));
    }
}
