@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4 bg-body pt-1">
    @if(env('DASHBOARD_FEED')==true)
        <x-dashboards.dashboard-feed />
    @else
        <x-elapse title="Boot the layout: "/>
        
        <div class="py-2" />
        <x-dashboards.bookmark-group />
        <x-elapse title="Bookmark group: " />

        <div class="py-2" />
        <x-renderer.project.project-overview-by-due-date />
        <x-elapse title="Project Overview: " />

        <div class="py-2" />
        <x-dashboards.my-view-groups />
        <x-elapse title="My View: "/>
        {{-- <x-dashboards.widget-groups /> --}}
        {{-- <x-elapse title="Widget group: "/> --}}
    @endif
</div>
    

@endsection