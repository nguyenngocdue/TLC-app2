@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', $typeReport)
@section('content')

{{-- @dump($modeParams) --}}
@section('content')
<div class="md:px-4 no-print">
    <div class="flex justify-end pb-2 pr-4 ">
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" />
    </div>
    <div class="flex justify-end  w-full rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports.mode-report :dataSource="$modeOptions" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
        <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
</div>
<div class="flex justify-center">
    <div class="md:px-4">
        @if (count($sheets))
        <div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border p-8">
            <x-reports.header2-report :dataSource="[]" />
            <x-renderer.table maxH="{{false}}" :dataSource="$sheets" :columns="[['dataIndex' => key(array_pop($sheets))]]" showNo="{{true}}" />
        </div>
        <x-renderer.page-break />
        @endif
        @foreach($tableDataSource as $idSheet => $data)
        <div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border p-8">
            <x-reports.header-report :dataSource="array_pop($data)" />
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource[$idSheet]" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
        <x-renderer.page-break />
        @endforeach
    </div>
</div>
@endsection
