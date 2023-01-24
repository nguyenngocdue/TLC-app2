@extends('layouts.app')

@section('topTitle', Str::appTitle($type))  
@section('title', "View All" )

@section('content')
<div class="flex justify-between">
    <x-form.search route="{{ route($type . '.index') }}" title="{{$searchTitle}}" />
    <div class="align-middle">
        <x-renderer.button title="Export this list to CSV"><i class="fa-regular fa-file-excel"></i></x-renderer.button>
        <x-renderer.button title="Print this list"><i class='fa fa-print'></i></x-renderer.button>
        <x-renderer.button title="Screen Settings"><i class='fa fa-cog'></i></x-renderer.button>
    </div>
    <x-form.per-page type="{{$type}}" route="{{ route('updateUserSettings') }}" page-limit="{{$pageLimit}}" />
</div>

<x-renderer.table showNo="true" :columns=" $columns" :dataSource="$dataSource" />
<br />
<x-modalSettings type="{{$type}}" />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection