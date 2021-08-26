@extends('layouts.account')

@section('content')
    <x-heading>
        Connect New Store
    </x-heading>

    <x-container class="p-6 lg:p-12">
        <div class="max-w-screen-xl mx-auto">
            <div class="grid max-w-2xl grid-cols-1 gap-10 mx-auto sm:gap-20 lg:max-w-3xl sm:grid-cols-3">
                <a href="{{ route('account-connect-shopify-authorize.create') }}" class="flex items-center justify-around sm:justify-center sm:flex-col ">
                    <div class="relative w-40 h-20 sm:w-full sm:aspect-ratio-1/1">
                        <img class="absolute object-center w-full h-full" src="{{ asset('images/logo-shopify.svg') }}" alt="Shopify" />
                    </div>
                    <x-button class="sm:mt-6" size="sm">Connect Now</x-button>
                </a>

                <a href="{{ route('account-connect-ecwid-authorize.create') }}" class="flex items-center justify-around sm:justify-center sm:flex-col ">
                    <div class="relative w-40 h-20 sm:w-full sm:aspect-ratio-1/1">
                        <img class="absolute object-center w-full h-full" src="{{ asset('images/logo-powershop.svg') }}" alt="PowerShop" />
                    </div>
                    <x-button class="sm:mt-6" size="sm">Connect Now</x-button>
                </a>

                <a href="{{ route('account-connect-shopcity-setup.create') }}" class="flex items-center justify-around sm:justify-center sm:flex-col ">
                    <div class="relative w-40 h-20 sm:w-full sm:aspect-ratio-1/1">
                        <img class="absolute object-center w-full h-full" src="{{ asset('images/logo-shopcity.svg') }}" alt="ShopCity" />
                    </div>
                    <x-button class="sm:mt-6" size="sm">Connect Now</x-button>
                </a>
            </div>
        </div>
    </x-container>
@endsection
