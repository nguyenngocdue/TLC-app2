@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    {{-- <x-dashboards.bookmark-group /> --}}
    {{-- <x-elapse title="Bookmark group: " /> --}}
    {{-- <x-dashboards.my-view-groups /> --}}
    {{-- <x-elapse title="My View: "/> --}}
    <div class="grid grid-cols-12 gap-3 my-5">
        <div class="col-start-4 col-span-6">
            <x-dashboards.my-view title="Monitored by Me" viewType="monitored_by_me"  />
        </div>
    </div>
</div>

@endsection