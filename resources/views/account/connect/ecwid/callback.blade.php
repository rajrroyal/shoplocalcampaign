@extends('layouts.blank')

@push('scripts')
    <script src="{{ mix('/js/account.js') }}"></script>
@endpush

@section('content')
    <div x-data="connectStore()" x-init="authCallback('{{ route('account-dashboard.index') }}')">
        <x-heading class="flex items-center justify-center h-40 text-center">Successfully Authorized PowerShop</x-heading>
        <div class="text-sm text-center text-gray-700">You can now close this window and return to your dashboard.</div>
    </div>
@endsection
