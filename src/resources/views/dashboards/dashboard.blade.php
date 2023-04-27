@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4">
    {{-- <x-dashboards.my-view-groups /> --}}
    <x-dashboards.widget-groups />
</div>

@endsection