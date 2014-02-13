@extends('layouts.master')
@section('content')

{{ Form::open(['route' => 'account.store', 'method' => 'post', 'class' => 'form-mini']) }}

    <h2>Create Account</h2>

    <div class="form-group">
        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', null, ['class' => 'form-control']) }}
        {{ $errors->first('username', "<span class=error>:message</span>") }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', null, ['class' => 'form-control']) }}
        {{ $errors->first('email', "<span class=error>:message</span>") }}
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', ['class' => 'form-control']) }}
        {{ $errors->first('password', "<span class=error>:message</span>") }}
    </div>

    <div class="form-group">
        {{ Form::label('password_confirmation', 'Password Again') }}
        {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
        {{ $errors->first('password_confirmation', "<span class=error>:message</span>") }}
    </div>

    <div class="form-group">
        {{ Form::submit('Create Account', ['class' => 'btn btn-primary']) }}
    </div>

{{ Form::close() }}

@stop