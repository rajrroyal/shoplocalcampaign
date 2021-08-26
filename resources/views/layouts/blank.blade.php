@extends('layouts._layout')

@section('content')
    @section('alert')
        {!! (new App\Services\Utilities\AlertService())->render() !!}
    @show

    @yield('content')
@overwrite