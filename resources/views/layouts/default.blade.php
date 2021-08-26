@extends('layouts._layout')

@section('content')
    @section('navbar')
        <x-layout.navbar />
    @show

    @section('alert')
        {!! (new App\Services\Utilities\AlertService())->render() !!}
    @show

    @yield('content')
@overwrite