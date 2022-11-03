@extends('layouts.app')
@section('content')

@php
$type = $type ?? 'info';
$title = $title ?? $type;
$message = $message ?? '[Message] is missing';
@endphp

<div class="flex ">
    <div id="alert-additional-content-4" class="m-auto mt-[15%] p-4 mb-4" role="alert">
        <x-feedback.alert type={{$type}} title={{$title}} message={{$message}} />
    </div>
</div>

@endsection