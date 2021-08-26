@extends('layouts._layout')

@push('scripts')
    <script src="{{ mix('/js/account.js') }}"></script>
@endpush

@section('content')
    <x-layout.sidebar>
        @section('alert')
            {!! (new App\Services\Utilities\AlertService())->render() !!}
        @show

        <main class="relative z-0 flex-1 px-4 pt-4 pb-6 overflow-y-auto focus:outline-none md:py-6 sm:px-6 md:px-8" tabindex="0">
            <div class="max-w-7xl">
                @yield('content')
            </div>
        </main>
    </x-layout.sidebar>
@overwrite
