@extends('layouts.app')
@section('content')

@php
$type = $type ?? 'info';
$title = $title ?? $type;
$message = $message ?? '[Message] is missing';

$class = \App\View\Components\Feedback\Alert::getClass($type);
$icon = \App\View\Components\Feedback\Alert::getIcon($type);
// dump($class);
@endphp

<div class="flex bg-body min-h-[1000px]">
    <div id="alert-additional-content-4" class="m-auto p-4 text-center" style="margin-top: 10%; margin-bottom: 10%;" role="alert">
        <div class="{{$class}} p-4 rounded shadow" style="min-width: 512px;">
            <i class="{{$icon}} text-4xl "></i>
            <x-renderer.heading class="text-center" level=4>{{$title}}</x-renderer.heading>
            {!! $message !!}
        </div>
    </div>
</div>

@endsection