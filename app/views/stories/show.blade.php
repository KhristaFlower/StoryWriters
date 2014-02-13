@extends('layouts.master')
@section('content')

<?php $invites = 0; $activeWriters = 0; ?>

<h2>{{ $story->title }}</h2>

<div class="row">

    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Collaborators</div>
            <ul class="list-group">
                @foreach($story->writers as $writer)

                    <?php // DETERMINE COLOURS TO SHOW FOR COLLABORATORS LIST
                        $class = "";
                        $editor = "";
                        if ($story->isInviteMode()) {
                            if ($story->user_id == $writer->id)
                                $class = " list-group-item-success";
                            elseif ($writer->inviteAccepted())
                                $class = " list-group-item-success";
                            else
                                $class = " list-group-item-warning";
                        } else {
                            // Highlight the user who's turn it is to write something.
                            if ($writer->id == $story->current_writer) {
                                $class = " list-group-item-info";
                                $editor = "<span class='glyphicon glyphicon-pencil'></span>";
                            }
                        }
                    ?>

                    <li class="list-group-item{{ $class }}">
                        @if($story->user_id == $writer->id)
                            <span class="glyphicon glyphicon-star"></span>
                            <?php $activeWriters++; ?>
                        @elseif($writer->pivot->active)
                            <span class="glyphicon glyphicon-ok"></span>
                            <?php $activeWriters++; ?>
                        @else
                            <span class="glyphicon glyphicon-envelope"></span>
                            <?php $invites++; ?>
                        @endif
                        {{ $writer->linkTo() }}
                        {{ $editor }}

                        {{-- OPTIONS --}}

                        <div class="pull-right">
                            {{-- LEAVE STORY --}}
                            @if($writer->isMe() && $writer->inviteAccepted() && !$story->isCreator() && $story->isInviteMode())
                                <a href="{{ URL::route('stories.leavestory', $story->id) }}" class="btn btn-xs btn-danger">
                                    Leave Story <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            @endif
                            {{-- LEAVE STORY END --}}
                            {{-- RESPOND TO INVITE --}}
                            @if($writer->isMe() && !$writer->inviteAccepted())
                                <a href="{{ URL::route('stories.acceptinvite', $story->id) }}" class="btn btn-xs btn-success">
                                    Accept <span class="glyphicon glyphicon-ok">
                                </a>
                                <a href="{{ URL::route('stories.declineinvite', $story->id) }}" class="btn btn-xs btn-danger">
                                    Decline <span class="glyphicon glyphicon-remove">
                                </a>
                            @endif
                            {{-- RESPOND TO INVITE END --}}
                            {{-- CREATOR CANCEL INVITE --}}
                            @if($story->isCreator() && !$writer->inviteAccepted() && $story->isInviteMode())
                                <a href="{{ URL::route('stories.cancelinvite', [$story->id, $writer->id]) }}" class="btn btn-xs btn-danger">
                                    Cancel Invite <span class="glyphicon glyphicon-remove">
                                </a>
                            @endif
                            {{-- CREATOR CANCEL INVITE END --}}
                        </div>

                        {{-- OPTIONS END --}}
                    </li>
                @endforeach

                {{-- INVITATION FORM --}}
                @if($story->isInviteMode() && ($story->isCreator() || $story->invitedCanInvite()))
                    <li class="list-group-item">
                        {{ Form::open([
                            'url' => "stories/{$story->id}/addwriter",
                            'method' => 'post' ,
                            'class' => 'form-horizontal'
                        ]) }}
                            <div class="form-group compact-form">
                                {{ Form::label('writer', 'Name', ['class' => 'col-xs-2 control-label']) }}
                                <div class="col-xs-8">
                                    {{ Form::text('writer', null, ['class' => 'form-control']) }}
                                </div>
                                {{ Form::submit('Add', ['class' => 'btn btn-default col-xs-2']) }}
                            </div>
                            {{ $errors->first('writer', "<span class=error>:message</span>") }}
                        {{ Form::close() }}
                    </li>
                @endif
                {{-- INVITATION FORM END --}}
            </ul>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Settings</div>
            <table class="table">
                @foreach($story->getSettings() as $name => $value)
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{-- PUBLIC JOIN LINK --}}
        @if($story->isInviteMode() && $story->isPublicInvite() && !$story->isLinked() && !$story->isFull())
            This story is public
            {{ HTML::linkRoute('stories.join', 'Join Now', [$story->id, $story->inviteKey()], ['class' => 'btn btn-primary']) }}
        @endif
        {{-- PUBLIC JOIN LINK END --}}
    </div>

</div>

<hr>

{{-- CREATOR CONTROL: START STORY --}}
@if($story->isCreator() && $story->isInviteMode())
    <div class="row">
        @if($invites != 0 || $activeWriters < 2)
            <div class="col-sm-4">
                <button class="btn btn-lg btn-success btn-block" disabled="disabled">Begin Story!</button>
            </div>
            <div class="col-sm-8">
                The story cannot start while there are unresolved invitations.
                You also require at least 2 active writers to begin.
            </div>
        @else
            <div class="col-sm-4">
                {{ HTML::linkRoute('stories.beginstory', 'Begin Story!', [$story->id], ['class' => 'btn btn-success btn-lg btn-block']) }}
            </div>
            <div class="col-sm-8">
                Once the story starts you will be unable to invite others to join it.
            </div>
        @endif
    </div>
    <hr>
@endif
{{-- CREATOR CONTROL: START STORY END --}}

{{-- STORY DISPLAY --}}
@if($story->isWriteMode())
    <h3>The story so far</h3>
    @foreach($story->segments as $segment)
        <span class="story-segment" title="{{$segment->user->username}}">{{ nl2br($segment->content) }}</span>
    @endforeach
    <hr>
@endif
{{-- STORY DISPLAY END --}}

{{-- WRITER CONTROL: WRITE A SEGMENT --}}
@if($story->myTurn() && $story->isWriteMode())
    <div class="row">
        <div class="col-sm-6">
            <h4>Compose your segment</h4>
            {{ Form::open(['route' => ['stories.composesegment', $story->id], 'method' => 'post']) }}

                <div class="form-group" id="segment-input-group">
                    {{ Form::label('segment', "Segment ({$story->min_words_per_segment} - {$story->max_words_per_segment} words)") }}
                    {{ Form::textarea('segment', null, ['rows' => 3, 'class' => 'form-control']) }}
                    {{ $errors->first('segment', "<span class=error>:message</span>") }}
                </div>

                <div class="form-group">
                    {{ Form::submit('Save Segment', ['class' => 'btn btn-primary']) }}
                </div>

            {{ Form::close() }}
        </div>
        <div class="col-sm-6">
            <h4>Ouput preview</h4>
            <div id="segment-output"></div>
            <br>
            <div class="alert alert-info" id="segment-status">
                @if($story->min_words_per_segment == $story->max_words_per_segment)
                    Write <b id="segment-value">{{ $story->min_words_per_segment }}</b> words for this segment.
                @else
                    Write between <b id="segment-min">{{ $story->min_words_per_segment }}</b> and
                    <b id="segment-max">{{ $story->max_words_per_segment }}</b> words for this segment.
                @endif
                <br>
                <b id="segment-output-wordcount">0</b> words written.<br><span id="segment-misc"></span>
                <hr>
                <small>The dynamic preview should match the final output although there
                may be small discrepencies.</small>
            </div>
        </div>
    </div>
@endif
{{-- WRITER CONTROL: WRITE A SEGMENT END --}}

@stop