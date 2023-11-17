@extends('layouts.app')
@section('content')

@php
$type = $type ?? 'info';
$title = $title ?? $type;
$message = $message ?? '[Message] is missing';
@endphp

<div class="flex ">
    <div id="alert-additional-content-4" class="m-auto p-4 text-center" style="margin-top: 10%; margin-bottom: 10%;" role="alert">
        <div class="border-yellow-300 bg-yellow-50 p-4 rounded text-yellow-700 shadow" style="min-width: 512px;">
            <i class="fa-duotone fa-circle-info text-4xl "></i>
            <x-renderer.heading xalign="center" level=4>{{$title}}</x-renderer.heading>
            {!! $message !!}
        </div>
    </div>
</div>

@endsection