@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $modeReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')
    <div class="px-4">
        <div class="justify-end pb-5"></div>
        <div
            class="no-print mb-5 w-full rounded-lg border border-gray-300 bg-gray-100 p-3 dark:border-gray-600 dark:bg-gray-800">
            <label for="" class="flex flex-1 text-lg font-bold text-gray-700 dark:text-white">Advanced Filter</label>
            {{-- <x-reports.mode-report :dataSource="$modeOptions" :column="$modeColumns" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" /> --}}
            {{-- <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" /> --}}
            <x-reports.parameter3-report :itemsSelected="$modeParams" modeOption="{{ $currentMode }}" :columns="$paramColumns"
                routeName="{{ $routeName }}" typeReport="{{ $typeReport }}" entity="{{ $entity }}" />
        </div>
        <div class="flex pb-2 pr-4">
            <x-reports.utility-report routeName="{{ $routeName }}" />
            <x-reports.per-page-report typeReport="{{ $typeReport }}" entity="{{ $entity }}"
                routeName="{{ $routeName }}" page-limit="{{ $pageLimit }}" formName="updatePerPage" />
        </div>

        @php
            $tc = "<x-reports.utility-report routeName='$routeName' ";
        @endphp
        {{-- <x-renderer.tag>Top Center</x-renderer.tag> --}}
        <x-renderer.table topCenterControl=" {!! $tc !!}" showNo={{ true }} :columns="$tableColumns"
            :dataSource="$tableDataSource" rotate45Width={{ $rotate45Width }} maxH="{{ $maxH }}"
            tableTrueWidth={{ $tableTrueWidth ? 1 : 0 }} />
    @endsection
</div>
