@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title )

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/>   
    <x-renderer.advanced-filter currentFilter="{{$currentFilter}}" :type="$type" :valueAdvanceFilters="$valueAdvanceFilters"/>
    <x-elapse title="Advanced filter: "/>
    @php 
    $abt = "<x-form.action-button-group type='$type' />"; 
    $route = route('updateUserSettings');
    $routeRestore = $trashed ? route($type.'.index') : route($type.'.trashed');
    $nameButtonHref = $trashed ? "View All" : "Recycle Bin";
    $iconButtonHref = $trashed ? "<i class='fa-solid fa-table-cells'></i>" : "<i class='fa-solid fa-trash'></i>";
    $btnType = $trashed ?  "secondary" : "danger";
    $p = "<x-form.per-page type='$type' route='$route' perPage='$perPage' />";
    $topL = "<div class='flex'>
                <form class='mr-1'>
                    <x-renderer.button outline=true type='$btnType' href='$routeRestore'>$iconButtonHref $nameButtonHref</x-renderer.button>
                </form>
                <x-form.refresh type='$type' route='$route' valueRefresh='$refreshPage'/>
            </div>";
    $am = $trashed ? "<x-form.action-multiple type='$type' restore='true'/>" : "<x-form.action-multiple type='$type'/>";
    @endphp
    <x-renderer.table showNo="true" :columns="$columns" :dataSource="$dataSource" 
        showPaginationTop="true"
        topCenterControl="{!! $abt !!}"
        topLeftControl="{!! $topL !!}"
        topRightControl="{!! $p !!}"
        bottomLeftControl="{!! $am !!}"
        bottomRightControl="{!! $p !!}"
        tableTrueWidth={{$tableTrueWidth}}
        />
        <x-elapse title="Table overhead (without columns): "/>
        <x-elapse total=1/>
</div>
<br />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection
