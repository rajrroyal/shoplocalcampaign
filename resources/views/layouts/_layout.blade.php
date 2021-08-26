<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @if(config('analytics.enabled'))
            @include('layouts.components.analytics')
        @endif
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', "Shop 2.0") {{ (config('app.env') !== 'production') ? '('. config('app.env') .')' : '' }}</title>
        <meta name="description" content="@yield('description', "Shop 2.0")" />

        <link rel="preload" as="style" href="{{ asset('/fonts/inter.css') }}" />
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}" />
        @stack('styles')

        @auth
            <link rel="preconnect" href="{{ url('/') }}:6001">
        @endauth
    </head>

    <body 
        x-data="{
            nav: false,
            search: false,
            profile: false,
            notifications: false
        }" 
        @swipeleft="nav = false"
        @swiperight="nav = true"
        class="min-h-screen"
    >
        @yield('content')

        @auth
            <x-layout.toasts />
        @endauth

        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
        </form>

        <script nonce="{{ csp_nonce() }}">
            const user_id = {{ auth()->user() ? auth()->user()->id : 'null' }};
        </script>
        
        @stack('scripts')
        
        @auth
            <script src="{{ mix('/js/user.js') }}"></script>
        @endauth

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
