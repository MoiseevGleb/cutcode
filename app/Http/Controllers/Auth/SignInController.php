<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Support\SessionRegenarator;

class SignInController extends Controller
{
    public function page(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.login');
    }

    public function handle(SignInFormRequest $request): RedirectResponse
    {
        if (! auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        SessionRegenarator::run();

        return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse
    {
        SessionRegenarator::run(function () {
            auth()->logout();
        });

        return redirect()->route('signIn');
    }
}
