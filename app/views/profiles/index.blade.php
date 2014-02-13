@extends('profiles.show')
@section('invitations')

@if(count($user->invitations->all()) > 0)
    <h3>Invitations</h3>
    @foreach(array_chunk($user->invitations->all(), 3) as $row)
        <div class="row">
            @foreach($row as $story)
                <div class="col-sm-4">
                    @include('partials.storydisplay')
                </div>
            @endforeach
        </div>
    @endforeach
@endif

@stop