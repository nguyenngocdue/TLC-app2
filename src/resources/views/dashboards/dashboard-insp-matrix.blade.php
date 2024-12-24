@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    <x-renderer.tab-pane class="" :tabs="$tabs" activeBgColor="gray" activeTextColor="blue">      
        <div class="grid grid-cols-12 gap-3 pt-2 mx-4 mb-4">
            <div class="col-span-12">
                @switch($tab)
                @case('ics')
                    <x-renderer.view-all-matrix-type.QaqcInspChklstShtsDashboard controller="{{$controller}}" />
                    @break
                @case('sqbts')
                    <x-renderer.matrix-for-report.ProdSequencesDashboard controller="{{$controller}}" />
                    @break
                @case('sign-off')
                    <div class="flex gap-1">
                        <x-dashboards.pre-sign-off-request />
                        <x-dashboards.sign-off-request />
                    </div>
                    @break
                @default
                    Need to implement tab content for {{$tab}}
                @break
                @endswitch
            </div>
        </div>
    </x-renderer.tab-pane>
</div>

@endsection