@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
    <div class="flex justify-end mr-5 mt-4">
        <x-reports2.report-absolute-time-range  :report="$report"/>
    </div>
    <x-reports2.report-filter :paramsUrl="$paramsUrl" :report="$report" />
    <div class="p-2 bg-body">
        @foreach ($pages as $key => $page)
            <x-reports2.report-page :page="$page" :report="$report"/>
            @if(($key + 1) != count($pages))            
                <x-renderer.page-break />
            @endif
        @endforeach
    </div>
@endsection
