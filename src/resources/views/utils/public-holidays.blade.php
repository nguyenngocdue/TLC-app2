@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')

@section('content')

<div class="bg-white p-5 border">
    <x-calendar.legend-public-holidays/>
    <div class="py-2"></div>
    <x-calendar.full-calendar-public-holidays />
</div>
@endsection
