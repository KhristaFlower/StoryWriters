@extends('layouts.app')

@section('wrapper')
    <div class="nav-clearance">
        @include('partials.navigation')
        <div class="jumbotron">
            <div class="container">
                @yield('jumbo')
            </div>
        </div>
        <div class="sub-wrapper">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
