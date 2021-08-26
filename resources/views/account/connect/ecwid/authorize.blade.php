@extends('layouts.account')

@section('content')
    <x-heading>Connect PowerShop</x-heading>

    <x-container class="p-6">
        <div x-data="connectStore()">
            <a @click="connectPowerShop('{{ $oAuthRedirectUrl }}')"
                @click.prevent 
                href="{{ $oAuthRedirectUrl }}"
            >
                <x-button type="button" class="mt-6">Authorize</x-button>
            </a>
        </div>
    </x-container>
@endsection
