@extends('layouts.blank')

@section('content')
<div class="flex items-start justify-center min-h-screen px-4 pt-12 pb-24 bg-gray-50 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div>
            <a href="{{ route('home') }}" class="block w-48 h-48 mx-auto">
                <img src="{{ asset('images/default-site-logo.svg') }}" />
            </a>
            <h2 class="mt-6 text-3xl font-extrabold leading-9 text-center text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-sm leading-5 text-center text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium transition duration-150 ease-in-out text-primary-600 hover:text-primary-500 focus:outline-none focus:underline">
                    Sign in
                </a>
            </p>
        </div>
        <form class="mt-8" action="{{ route('register.store') }}" method="POST">
            @csrf

            <div>
                <x-input.label for="name">Name</x-input.label>
                <x-input.text id="name" name="name" type="text" required value="{{ old('name', '') }}" />
            </div>

            <div class="mt-6">
                <x-input.label for="email">Email Address</x-input.label>
                <x-input.text id="email" name="email" type="email" required value="{{ old('email', '') }}" />
            </div>

            <div class="mt-6">
                <x-input.label for="password">Password</x-input.label>
                <x-input.text id="password" name="password" type="password" required />
            </div>

            <div class="mt-6">
                <x-input.label for="password_confirmation">Confirm Password</x-input.label>
                <x-input.text id="password_confirmation" name="password_confirmation" type="password" required />
            </div>

            <div class="mt-6">
                <x-button fullWidth type="submit">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 transition duration-150 ease-in-out text-primary-500 group-hover:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Register
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection
