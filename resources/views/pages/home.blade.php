@extends('layouts.jumbo')

@section('jumbo')
    <h1 class="text-center">Story Writers</h1>
    <p class="lead text-center">
        Team up with others to write cool things and share them with everyone.
    </p>
@endsection

@section('content')

    <p class="lead text-center">
        Inspired by the 3 word story, Story Writers lets you set the rules and ensures that
        everybody follows along fairly. Configure your settings for the
        story you want to create and see where it goes from there.
    </p>

    <hr>

    <div class="row">
        <div class="col-sm-4 text-center">
            <h3>Create an account</h3>
            <p>
                Creating an account is quick, simple, and you can get writing right way.
            </p>
        </div>
        <div class="col-sm-4 text-center">
            <h3>Join some friends</h3>
            <p>
                Create a story with your friends, or join a public story with strangers.
            </p>
        </div>
        <div class="col-sm-4 text-center">
            <h3>Write a story</h3>
            <p>
                Write whatever you want together, who knows where you'll end up?
            </p>
        </div>
    </div>

    <hr>

    <p class="lead text-center">
        What are you waiting for?<br>
        Create your account, get your friends involved and write a story!<br>
        <br>
        <a href="#" class="btn btn-primary btn-lg">Join Now!</a>
{{--        {{ Html::linkRoute('account.create', "Join Now!", null, ['class' => 'btn btn-primary btn-lg']) }}--}}
    </p>

    <hr>

    <p class="lead text-center">
        If you'd like to find out more information about all aspects of this site,
        navigate to the About tab or click the button below.<br><br>
        {!! Html::link('about', "Find out more", ['class' => 'btn btn-info btn-lg']) !!}
    </p>

    <hr>

@endsection
