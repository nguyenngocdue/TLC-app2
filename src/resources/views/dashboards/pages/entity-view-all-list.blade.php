@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', 'View All')

@section('content')
    <div class="px-4 pt-2 bg-body min-h-[700px]">
        <x-elapse title="Bootrap: " duration="{{ $frameworkTook }}" />
        <x-elapse title="ViewAllController: " />

        @if ($showAdvanceFilterForm)
            <x-renderer.advanced-filter trashed="{{ $trashed }}" currentFilter="{{ $currentFilter }}"
                type="{{ $type }}" typeModel="{{ $typeModel }}" :valueAdvanceFilters="$valueAdvanceFilters" />
        @endif

        <x-renderer.view-all.view-all-type-selector type="{{ $type }}" viewType="list-view" />
        <x-renderer.view-all.view-all-type-list 
            :tabPane="$tabPane" 
            type="{{ $type }}" 
            perPage='{{ $perPage }}'
            refreshPage="{{ $refreshPage }}" 
            trashed="{{ $trashed }}" 
            :columns="$columns" 
            :dataSource="$dataSource"
            tableTrueWidth="{{ $tableTrueWidth }}" 
            />
    </div>
@endsection
