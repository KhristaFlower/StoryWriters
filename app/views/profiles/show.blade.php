@extends('layouts.master')
@section('content')

<h2>{{ $user->username }}</h2>

@yield('invitations')

<h3>Created Stories</h3>

@foreach(array_chunk($user->started->all(), 3) as $row)
    <div class="row">
        @foreach($row as $story)
            <div class="col-sm-4">
                @include('partials.storydisplay')
            </div>
        @endforeach
    </div>
@endforeach

<h3>Collaborations</h3>

@foreach(array_chunk($user->collaborations->all(), 3) as $row)
    <div class="row">
        @foreach($row as $story)
            <div class="col-sm-4">
                @include('partials.storydisplay')
            </div>
        @endforeach
    </div>
@endforeach

@stop