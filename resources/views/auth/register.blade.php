@extends('layouts.form')

@section('form')

    <h2>Create Account</h2>

    {!! Form::open(['route' => 'register', 'method' => 'post']) !!}

    <div class="form-group">
        {!! Form::label('username', 'Username') !!}
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
        {!! $errors->first('username', "<span class='error'>:message</span>") !!}
    </div>

    <div class="form-group">
        {!! Form::label('email', 'Email') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('email', "<span class='error'>:message</span>") !!}
    </div>

    <div class="form-group">
    	{!! Form::label('password', 'Password') !!}
    	{!! Form::password('password', ['class' => 'form-control']) !!}
    	{!! $errors->first('password', "<span class='error'>:message</span>") !!}
    </div>

    <div class="form-group">
    	{!! Form::label('password_confirmation', 'Confirm Password') !!}
    	{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
    	{!! $errors->first('password_confirmation', "<span class='error'>:message</span>") !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Create Account', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

@endsection
