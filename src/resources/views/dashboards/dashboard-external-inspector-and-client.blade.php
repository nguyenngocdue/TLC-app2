@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    <div class="grid grid-cols-12 gap-3 my-5">
        <div class="col-span-10">
            {{-- <x-dashboards.my-view title="Monitored by Me" viewType="monitored_by_me"  /> --}}
            <x-renderer.dashboard-filter.qaqc_insp_chklst_shts
                type="qaqc_insp_chklst_shts"
                :viewportParams="$viewportParams"
                :dataSource="$dataSource"
            />
            <x-renderer.matrix-for-report.QaqcInspChklstShts 
                subProjectId="{{$viewportParams['sub_project_id']}}" 
                prodRoutingId="{{$viewportParams['prod_routing_id']}}" 
                qaqcInspTmplId="{{$viewportParams['qaqc_insp_tmpl_id']}}" 
                showOnlyInvolved="{{$showOnlyInvolved ?? false}}"
                />
        </div>
    </div>
</div>

@endsection