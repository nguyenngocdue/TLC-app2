@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', Str::ucfirst($typeReport))
@section('content')
@php
// dd($tableDataSource);
$dataHeading = array_values($tableDataSource->items())[0][0] ?? [];
@endphp

<div class="md:px-4 no-print">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.mode-report :dataSource="$modeOptions" :column="$modeColumns" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
        {{-- <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" /> --}}
        {{-- <x-reports.parameter2-report :getSettingParams="$getSettingParams" :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" /> --}}
        <x-reports.parameter3-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />

    </div>
    <div class="flex pb-2 pr-4 ">
        <x-reports.utility-report routeName="{{$routeName}}" />
    </div>
</div>
<div class="flex justify-center">
    <div class="md:px-4">
        @if (count($sheets))
        <div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border p-8">
            <x-reports.header-info-doc-report :dataSource="$dataHeading" />
            <x-renderer.table maxH="{{false}}" :dataSource="$sheets" :columns="[['dataIndex' => key(array_pop($sheets))]]" showNo="{{true}}" />
        </div>
        <x-renderer.page-break />
        @endif
        @foreach($tableDataSource as $idSheet => $data)
        <div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border p-8">
            <x-reports.header-sheet-report :dataSource="array_pop($data)" />
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource[$idSheet]" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
        <x-renderer.page-break />
        @endforeach
    </div>
</div>
@endsection
