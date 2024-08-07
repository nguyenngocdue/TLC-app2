@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
    <x-reports2.filter-report :paramsUrl="$paramsUrl" :filterDetails="$filterDetails" :filterModes="$filterModes" :report="$report" />
    <div class="p-2 bg-body">
        @foreach ($pages as $page)
            <x-reports2.page-report :page="$page" reportId="{{ $reportId }}" />
        @endforeach
    </div>
@endsection
