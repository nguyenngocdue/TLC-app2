@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
<div class="p-2 bg-body">
    {{-- <div class="border rounded bg-gray-200 p-10 text-center"> --}}
        @foreach ($pages as $page)
            <x-reports2.page-report :page="$page"/>
        @endforeach
    {{-- </div>    --}}
</div>
@endsection
