@extends('layouts.default')

@section('content')
    <div class="bg-white">
        <div class="max-w-screen-xl px-4 py-12 mx-auto text-center sm:px-6 lg:py-16 lg:px-8">
            <h2 class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 sm:text-4xl sm:leading-10">
                Home Page
            </h2>
            <br>
            <a href="{{route('account-dashboard.index')}}">Click here to go To Dashboard</a>
        </div>
    </div>
@endsection
