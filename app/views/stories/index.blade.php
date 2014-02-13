@extends('layouts.master_pagehead')
@section('pagehead')

<h1>Stories</h1>
<p>
    Browse, create, join, and read stories
</p>

@stop
@section('content')

<div class="row space">
    <div class="col-sm-6 col-lg-4">
        Filter
        {{ HTML::linkRoute('stories.finished', 'Finished', null, ['class' => 'btn btn-default']) }}
        {{ HTML::linkRoute('stories.ongoing', 'Ongoing', null, ['class' => 'btn btn-default']) }}
        {{ HTML::linkRoute('stories.starting', 'Starting', null, ['class' => 'btn btn-default']) }}
    </div>
    <div class="col-sm-6 col-lg-4">
        {{ Form::open(['method' => 'GET']) }}
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                {{ Form::input('search', 'q', null, ['placeholder' => "Search", 'class' => 'form-control']) }}
                <span class="input-group-btn">
                    {{ Form::submit('Search', ['class' => 'btn btn-default']) }}
                </span>
            </div>
        {{ Form::close() }}
    </div>
    <div class="col-sm-6 col-lg-4">
        @if(Auth::check())
            {{ HTML::linkRoute('stories.create', 'Create a new story', null, ['class' => 'btn btn-primary']) }}
        @else
            {{ HTML::linkRoute('login', 'Login', null, ['class' => 'btn btn-default']) }}
            to start a story.
        @endif
    </div>
</div>

<hr>

@if($stories->count())
    @foreach ($stories as $story)
        <div class="story-block">
            <div class="title">
                {{ HTML::linkRoute('stories.show', $story->title, $story->id) }}
            </div>
            <?php $details = array(
                'Creator' => $story->creator->linkTo(),
                'Writers' => (count($story->writers)),
                'Theme' => $story->theme
            ); ?>

            @foreach ($details as $key => $value)
                <div class="pair row">
                    <div class="key col-xs-4">{{ $key }}</div>
                    <div class="value col-xs-8">{{ $value }}</div>
                </div>
            @endforeach
        </div>
    @endforeach

    {{ $stories->links() }}

@else

    <p class="lead">
        No results found.
    </p>

@endif

@stop