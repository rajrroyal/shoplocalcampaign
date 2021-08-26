@extends('layouts.account')

@section('content')
    <x-heading>Connect Shopify</x-heading>

    <x-container x-data="connectStore()" class="p-6">
        <form @submit="connectShopify($event)" @submit.prevent>
            <x-input.label for="shop">Shop Name</x-input.label>
            <div class="flex max-w-xs rounded-md shadow-sm">
                <input id="shop" value="{{ $shop }}" name="shop" type="text" class="flex-1 block w-full px-3 py-2 rounded-none form-input rounded-l-md sm:text-sm sm:leading-5" placeholder="daves-new-world" />
                <span class="inline-flex items-center px-3 text-gray-500 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 sm:text-sm">
                    .myshopify.com
                </span>
            </div>
            <input type="hidden" name="client_id" value="{{ $client_id }}" />
            <input type="hidden" name="scope" value="{{ $scope }}" />
            <input type="hidden" name="state" value="{{ $state }}" />
            <input type="hidden" name="redirect_uri" value="{{ $redirect_uri }}" />
            <x-button type="submit" class="mt-6">Continue</x-button>
        </form>
    </x-container>
@endsection
