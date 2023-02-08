@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All" )

@section('content')
<div class="flex flex-wrap pb-5">
    <x-form.search route="{{ route($type . '.index') }}" title="{{$searchTitle}}" />
    <div class="w-full lg:w-1/3 p-2 lg:p-0 items-center">
            <x-renderer.button title="Export this list to CSV"><i class="fa-duotone fa-file-csv"></i></x-renderer.button>
            <x-renderer.button title="Print this list"><i class='fa-duotone fa-print'></i></x-renderer.button>
            <x-renderer.button title="Screen Settings"><i class="fa-duotone fa-gear"></i></x-renderer.button>
    </div>
    <x-form.per-page type="{{$type}}" route="{{ route('updateUserSettings') }}" page-limit="{{$pageLimit}}" />
</div>
<x-renderer.filter :type="$type"  :valueAdvanceFilters="$valueAdvanceFilters"/>
<x-renderer.table showNo="true" :columns=" $columns" :dataSource="$dataSource" />
<br />
<x-modalSettings type="{{$type}}" />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection