@extends('layouts.account')

@section('content')
    <x-heading>
        Connect ShopCity
    </x-heading>
    
    <x-container class="p-6">
        <form action="{{ route('account-connect-shopcity-setup.store') }}" method="POST">
            @csrf
            <x-input.label for="shop">ShopCity Profile URL</x-input.label>
            <x-input.text class="max-w-md" id="shop" name="profile" placeholder="http://www.shopmidland.com/thehub" type="text" />
            
            <x-button type="submit" class="mt-6">Continue</x-button>
        </form>
    </x-container>
@endsection
