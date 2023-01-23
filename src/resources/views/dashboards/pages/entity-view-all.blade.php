@extends('layouts.app')

@section('title', $title )
@section('content')

<div class="grid grid-cols-2 gap-5">
    <x-form.search route="{{ route($type . '.index') }}" title="{{$searchTitle}}" />
    <div class="grid justify-items-end">
        <x-form.per-page type="{{$type}}" route="{{ route('updateUserSettings') }}" page-limit="{{$pageLimit}}" />
    </div>
</div>

<x-renderer.table showNo="true" :columns=" $columns" :dataSource="$dataSource" />
<br />
<x-modalSettings type="{{$type}}" />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection