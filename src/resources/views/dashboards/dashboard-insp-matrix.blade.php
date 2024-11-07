@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')
@php
    $showOnlyInvolved=$showOnlyInvolved ?? false;
@endphp

<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    <div class="grid grid-cols-12 gap-3 my-5">
        <div class="col-span-12">
            <x-renderer.view-all-matrix-type.QaqcInspChklstShtsDashboard
                controller="{{$controller}}"
                />
        </div>
    </div>
</div>

@endsection