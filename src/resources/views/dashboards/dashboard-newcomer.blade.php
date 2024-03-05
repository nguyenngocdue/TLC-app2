@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')
{{-- @dump($viewportParams) --}}
<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    <div class="grid grid-cols-12 gap-3 my-5">
        <div class="col-span-10">
            {{-- <x-dashboards.my-view title="Monitored by Me" viewType="monitored_by_me"  /> --}}
            Hi NEWCOMER,
        </div>
    </div>
</div>

@endsection