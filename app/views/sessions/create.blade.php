@extends('layouts.master')
@section('content')

{{ Form::open(['route' => 'sessions.store', 'method' => 'post', 'class' => 'form-mini']) }}

    <h2 class="form-mini-heading">Please sign in</h2>

    <div class="form-group">
        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::submit('Login', ['class' => 'btn btn-primary']) }}
    </div>

{{ Form::close() }}

@stop