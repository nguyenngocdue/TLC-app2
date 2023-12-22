@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')

@section('content')

<div class="bg-white p-10 border">
    <x-calendar.full-calendar-public-holidays />
</div>
@endsection
