@extends('layouts.form')

@section('form')

    <h2>Sign in</h2>

    {!! Form::open(['route' => 'login', 'method' => 'post']) !!}

    <div class="form-group">
        {!! Form::label('username', 'Username') !!}
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
    	{!! Form::label('password', 'Password') !!}
    	{!! Form::password('password', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Login', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

@endsection
