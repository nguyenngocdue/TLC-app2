@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All" )

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/>   
    <x-renderer.advanced-filter currentFilter="{{$currentFilter}}" :type="$type" :valueAdvanceFilters="$valueAdvanceFilters"/>
    <x-elapse title="Advanced filter: "/>
    @php 
    $abt = "<x-form.action-button-group type='$type' />"; 
    $route = route('updateUserSettings');
    $p = "<x-form.per-page type='$type' route='$route' perPage='$perPage' />";
    $topL = "<x-form.refresh type='$type' route='$route' valueRefresh='$refreshPage'/>";
    $am = "<x-form.action-multiple type='$type'/>";
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
