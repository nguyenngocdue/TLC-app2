@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
<x-reports2.report-filter :paramsUrl="$paramsUrl" :report="$report" />
<div class="p-2 bg-gray-100 dark:bg-gray-800">
    @foreach ($pages as $key => $page)
        @if(!$page->is_active) @continue @endif
        <x-reports2.report-page :page="$page" :report="$report" :currentParams="$currentParams"/>
        @if(($key + 1) != count($pages))            
        <x-renderer.page-break />
        @endif            
    @endforeach
</div>
    
<x-modals.modal-report-chart modalId="modal-report-chart"/>
<script>
    function showModal(data) {        
        const alpineRef = document.querySelector('[x-ref="alpineRef"]');
        const alpineComponent = alpineRef.__x.$data.toggleModal('modal-report-chart', data);
    }
</script>

@endsection
