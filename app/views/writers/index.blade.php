@extends('layouts.master_pagehead')
@section('pagehead')

<h1>Writers</h1>
<p>
    The big list of writers
</p>

@stop
@section('content')

<ul>
@foreach($writers as $writer)
    <li>{{ $writer->linkTo() }}</li>
@endforeach
</ul>

@stop