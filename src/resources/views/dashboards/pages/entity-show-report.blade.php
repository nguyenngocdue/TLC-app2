@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
    <x-reports2.report-filter :paramsUrl="$paramsUrl" :report="$report" />
    <div class="p-2 bg-gray-100 dark:bg-gray-800">
        @foreach ($pages as $key => $page)
            <x-reports2.report-page :page="$page" :report="$report"/>
            @if(($key + 1) != count($pages))            
                <x-renderer.page-break />
            @endif
        @endforeach
    </div>
@endsection
