@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title' ) View All <sub>{!! $title !!}</sub> @endsection

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    <x-renderer.advanced-filter trashed="{{$trashed}}" currentFilter="{{$currentFilter}}" type="{{$type}}" typeModel="{{$typeModel}}" :valueAdvanceFilters="$valueAdvanceFilters"/>
    <x-renderer.view-all-type-selector type="{{$type}}" viewType="list-view" />
    <x-renderer.view-all-type-list 
        :tabPane="$tabPane" 
        type="{{$type}}" 
        perPage='{{$perPage}}' 
        refreshPage="{{$refreshPage}}" 
        trashed="{{$trashed}}" 
        :columns="$columns"
        :dataSource="$dataSource"
        tableTrueWidth="{{$tableTrueWidth}}"
        />
</div>
@endsection
