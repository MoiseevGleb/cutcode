@extends('layouts.app')

@section('content')
    @auth
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            @method('DELETE')
            <x-forms.primary-button>Выйти</x-forms.primary-button>
        </form>
    @endauth
@endsection
