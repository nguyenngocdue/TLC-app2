@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')

@if ($showModeOnParam)
<div class="grid grid-cols-12 gap-1">
        <div class="col-span-2">
            <div class="no-print justify-end pl-4 pt-5">
                <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
                    <x-reports.dropdown8    
                        title="Mode" 
                        name="forward_to_mode" 
                        routeName="{{$routeName}}"
                        :allowClear="false"
                        :dataSource="$modeParamDataSource"
                        typeReport='{{$typeReport}}'
                        entity='{{$entity}}'
                        modeOption='{{$mode}}'
                        :itemsSelected="$params" 
                        />
                </div>
            </div>
        </div>

        <div class="col-span-10">
            <div class="px-4">
                @include('components.reports.shared-parameter')
                @include('components.reports.show-layout2')
            </div>
        </div>
</div>
@endif

<div class="px-4">
    @if (!$showModeOnParam)
        @include('components.reports.shared-parameter')
    @endif

    @if(!empty($legendColors))
    <x-renderer.card class="mb-5">
        <x-reports.color-legend-report :dataSource="$legendColors" />
    </x-renderer.card>
    @endif
    @php
        $tl = "<div></div>";
        $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
        $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
    @endphp
    @if($typeOfView === 'report-pivot')
    <x-renderer.report.pivot-table 
                    showNo={{true}} 
                    modeType="{{$modeType}}"  
                    :tableDataHeader="$tableDataHeader" 
                    :tableColumns="$tableColumns" 
                    :params="$params" 
                    :dataSource="$tableDataSource"  
                    tableTrueWidth={{$tableTrueWidth?1:0}} 
                    page-limit="{{$pageLimit}}" 
                    headerTop=10
                    maxH="{{$maxH}}" 
                    
                    showPaginationTop="true"
                    topLeftControl="{!!$tl!!}" 
                    topCenterControl="{!!$tc!!}" 
                    topRightControl="{!!$tr!!}" 
                    />
    @else
    <x-renderer.table 
                    showNo={{true}} 
                    :dataHeader="$tableDataHeader" 
                    :columns="$tableColumns" 
                    :dataSource="$tableDataSource" 
                    rotate45Width={{$rotate45Width}} 
                    maxH="{{$maxH}}" 
                    tableTrueWidth={{$tableTrueWidth?1:0}}
                    page-limit="{{$pageLimit}}" 


                    showPaginationTop="true"
                    topLeftControl="{!!$tl!!}" 
                    topCenterControl="{!!$tc!!}" 
                    topRightControl="{!!$tr!!}" 
                    /> 
    @endif
</div>
@endsection
