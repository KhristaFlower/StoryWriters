@extends('layouts.master')
@section('content')

<?php
$invite_options = array(
    1 => 'Creator Only',
    2 => 'Creator & Invited Only',
    3 => 'Anyone with link (hidden from search)',
    4 => 'Anyone (available to search)'
);
$write_options = array(
    1 => 'Turn based',
    2 => 'Random order',
    3 => 'Free for all'
);
$attributes = array(
    'class' => 'form-control'
);
$error_format = "<span class=error>:message</span>";
?>

<h2>Create a new story</h2>

{{ Form::open(['route' => 'stories.index', 'method' => 'post']) }}

    <h3>Information</h3>

    <div class="form-collection">
        <div class="form-group">
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', null, $attributes) }}
            {{ $errors->first('title', $error_format) }}
        </div>

        <div class="form-group">
            {{ Form::label('theme', 'Theme') }}
            {{ Form::text('theme', null, $attributes) }}
            {{ $errors->first('theme', $error_format) }}
        </div>
    </div>

    <h3>Settings</h3>

    <div class="form-collection">
        <div class="form-group">
            {{ Form::label('invite_mode', 'Invite Mode') }}
            {{ Form::select('invite_mode', $invite_options, null, $attributes) }}
            {{ $errors->first('invite_mode', $error_format) }}
        </div>

        <div class="form-group">
            {{ Form::label('max_writers', 'Maximum Number of Writers') }}
            {{ Form::text('max_writers', 1, $attributes) }}
            {{ $errors->first('max_writers', $error_format) }}
        </div>

        <div class="form-group">
            {{ Form::label('min_words_per_segment', 'Minimum Number of Words Per Segment') }}
            {{ Form::text('min_words_per_segment', null, $attributes) }}
            {{ $errors->first('min_words_per_segment', $error_format) }}
        </div>

        <div class="form-group">
            {{ Form::label('max_words_per_segment', 'Maximum Number of Words Per Segment') }}
            {{ Form::text('max_words_per_segment', null, $attributes) }}
            {{ $errors->first('max_words_per_segment', $error_format) }}
        </div>

        <div class="form-group">
            {{ Form::label('write_mode', 'Write Mode') }}
            {{ Form::select('write_mode', $write_options, null, $attributes) }}
            <div class="help-block">
                Turn Based has writers create a segment in the order they were invited to the story.
                Random will select another user to write a segment after each submission.
                Free For All writing allows users to post in any order except after themselves.
            </div>
            {{ $errors->first('write_mode', $error_format) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::submit('Create Story', ['class' => 'form-control btn btn-primary']) }}
    </div>

{{ Form::close() }}

@stop