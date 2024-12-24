@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
<x-reports2.report-filter :paramsUrl="$paramsUrl" :report="$report" />

<div class="p-2 bg-gray-100 dark:bg-gray-800 relative">

    @if (App\Utils\Support\CurrentUser::isAdmin())
        <button type="button" style="top : -55px; right: 40px " class="absolute  text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-2 py-1 text-center me-2">
            <a target="blank" href="{{route('rp_reports.edit',$report->id)}}" >
                <i class="fa-solid fa-eye"></i> Original Report
            </a>
        </button>    
    @endif



    @foreach ($pages as $key => $page)
        @if(!$page->is_active) @continue @endif
        @php
            $hasIteratorBlock = $page->iterator_block_id && $page->iterator_block_id != 1 ? true : false;
        @endphp
        @if ($hasIteratorBlock)
            <x-reports2.report-dynamic-page :page="$page" :report="$report" :currentParams="$currentParams" hasIteratorBlock={{$hasIteratorBlock}}/>
        @else
            <x-reports2.report-page :page="$page" :report="$report" :currentParams="$currentParams" hasIteratorBlock={{$hasIteratorBlock}}/>
        @endif 

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
