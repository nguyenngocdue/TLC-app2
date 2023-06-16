@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title )

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    <x-renderer.advanced-filter trashed="{{$trashed}}" currentFilter="{{$currentFilter}}" type="{{$type}}" typeModel="{{$typeModel}}" :valueAdvanceFilters="$valueAdvanceFilters"/>
    <x-renderer.view-all-type-selector type="{{$type}}" />
    <x-renderer.tab-pane :tabs="$tabPane">
        @php 
        // $abt = "<x-form.action-button-group type='$type' />"; 
        $route = route('updateUserSettings');
        $perPage = "<x-form.per-page type='$type' route='$route' perPage='$perPage'/>";
        $actionButtonGroup = "<div class='flex'>
                    <x-form.refresh type='$type' route='$route' valueRefresh='$refreshPage'/>
                    <x-form.action-button-group type='$type' />
                </div>";
        $actionMultipleGroup = $trashed ? "<x-form.action-multiple type='$type' restore='true'/>" : "<x-form.action-multiple type='$type'/>";
        @endphp
        <div class="p-2 bg-white">
            <x-renderer.table 
                showNo="true" 
                :columns="$columns" 
                :dataSource="$dataSource" 
                showPaginationTop="true"
                topLeftControl="{!! $actionButtonGroup !!}"
                topRightControl="{!! $perPage !!}"
                bottomLeftControl="{!! $actionMultipleGroup !!}"
                bottomRightControl="{!! $perPage !!}"
                tableTrueWidth={{$tableTrueWidth}}
                />
        </div>
    </x-renderer.tab-pane>
</div>
{{-- <script src="{{ asset('js/renderprop.js') }}"></script> --}}
@endsection
