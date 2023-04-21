@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4">
    @php $oldTitle = ""; @endphp
    @foreach($allWidgetGroups as $widgetGroup)
        
        <x-renderer.card title="{{$widgetGroup['title']}}">
                <div class="mb-8 grid gap-6 2xl:grid-cols-4 xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1">
                @foreach($widgetGroup['children'] as $widget)
                @php
                    $infoDataQuery = [
                        'table_a' => $widget['table_a'],
                        'table_b' => $widget['table_b'],
                        'key_a' => $widget['key_a'],
                        'key_b' => $widget['key_b'],
                    ]
                @endphp
                    <x-renderer.card title="{{$widget['title_b']}}">
                        <x-renderer.report.widget title="Total {{$widget['title_a']}}" figure="{{$widget['meta']['max']}}"/>
                        <br/>
                        <x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" :infoDataQuery="$infoDataQuery" />
                    </x-renderer.card>
                @endforeach
            </div>
        </x-renderer.card>
    @endforeach
</div>
@endsection