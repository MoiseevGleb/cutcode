<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SignUpController extends Controller
{
    public function page(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.sign-up');
    }

    public function handle(SignUpFormRequest $request, RegisterUserContract $action): RedirectResponse
    {
        $user = $action(NewUserDTO::fromRequest($request));

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
