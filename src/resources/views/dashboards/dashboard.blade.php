@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4">
    <x-dashboards.bookmark-group />
    <x-dashboards.my-view-groups />
    <x-dashboards.widget-groups />
    <x-elapse />
    <x-elapse total=1/>
</div>

@endsection