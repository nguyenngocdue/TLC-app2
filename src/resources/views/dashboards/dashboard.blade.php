@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4">
    <x-elapse title="Boot the layout: "/>
    <x-dashboards.bookmark-group />
    <x-elapse title="Bookmark group: " />
    <x-dashboards.my-view-groups />
    <x-elapse title="My View: "/>
    <x-dashboards.widget-groups />
    <x-elapse title="Widget group: "/>
</div>

@endsection