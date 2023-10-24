@extends('layouts.auth')

@section('title', 'Восстановление пароля')
@section('content')
    <x-forms.auth-forms action="{{ route('password.update') }}" method="POST">
        @csrf
        <x-slot:title>Восстановление пароля</x-slot:title>

        <input type="hidden" value="{{ $token }}" name="token">
        <x-forms.text-input
            name="email"
            type="email"
            placeholder="E-mail"
            required
            value="{{ request('email') }}"
            :isError="$errors->has('email')"
        />
        @error('email')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password"
            type="password"
            placeholder="Пароль"
            required
            :isError="$errors->has('password')"
        />
        @error('password')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password_confirmation"
            type="password"
            placeholder="Повторите пароль"
            required
            :isError="$errors->has('password_confirmation')"
        />
        @error('password_confirmation')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.primary-button>Обновить пароль</x-forms.primary-button>

        <x-slot:socialAuth></x-slot:socialAuth>

        <x-slot:buttons>
            <div class="space-y-3 mt-5">
                <div class="text-xxs md:text-xs"><a href="{{ route('login') }}" class="text-white hover:text-white/70 font-bold">Вспомнил пароль</a></div>
            </div>
        </x-slot:buttons>

    </x-forms.auth-forms>
@endsection
