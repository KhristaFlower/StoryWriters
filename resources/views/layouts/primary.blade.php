@extends('layouts.app')

@section('wrapper')
    <div class="nav-clearance">
        @include('partials.navigation')
        <div class="sub-wrapper">
            @yield('content')
        </div>
    </div>
@endsection
