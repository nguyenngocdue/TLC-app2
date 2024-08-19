@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
    <x-reports2.filter-report :paramsUrl="$paramsUrl" :report="$report" />
    <div class="p-2 bg-body">
        @foreach ($pages as $key => $page)
            <x-reports2.page-report :page="$page" :report="$report"/>
            @if(($key + 1) != count($pages))            
                <x-renderer.page-break />
            @endif
        @endforeach
    </div>
@endsection
