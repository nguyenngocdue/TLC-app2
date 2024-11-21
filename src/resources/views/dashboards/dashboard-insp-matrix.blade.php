@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    <x-renderer.tab-pane class="" :tabs="$tabs">      
        <div class="grid grid-cols-12 gap-3 pt-2 mx-4 mb-4">
            <div class="col-span-12">
                @if($tab == 'ics')
                    <x-renderer.view-all-matrix-type.QaqcInspChklstShtsDashboard controller="{{$controller}}" />
                @elseif($tab == 'sqbts')
                    <x-renderer.matrix-for-report.ProdSequencesDashboard controller="{{$controller}}" />
                @endif
            </div>
        </div>
    </x-renderer.tab-pane>
</div>

@endsection