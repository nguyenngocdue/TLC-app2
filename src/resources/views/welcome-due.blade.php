@extends('layouts.app')
@section('content')
@php
#dump($widget);
@endphp

{{-- <x-elapse title="Widget group: " />
<div class="w80 h-80 bg-green-600 ">
    <x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" :widgetParams="$widget['params']" />
</div>
<x-dashboards.widget-groups /> --}}

{{-- <x-renderer.card title="">
    <div class="mb-8 grid gap-6 2xl:grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
        <x-renderer.card title="{{$widget['title_b']}}" tooltip="{{$widget['name']}}">
            <x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" :widgetParams="$widget['params']" />
        </x-renderer.card>
    </div>
</x-renderer.card> --}}
<x-dashboards.widget-groups /> 
 <x-renderer.report.pivot-chart :widget="[]"></x-renderer.report.pivot-chart>

 
@endsection
