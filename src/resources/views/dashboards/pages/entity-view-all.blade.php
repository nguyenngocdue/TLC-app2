@extends('layouts.app')

@section('title', $title )
@section('content')

<div class="grid grid-cols-2 gap-5">
    <x-form.search action="{{ route($type . '.index') }}">
    </x-form.search>
    <div class="grid justify-items-end">
        <x-form.per-page type="{{$type}}" action="{{ route('updateUserSettings') }}" page-limit="{{$pageLimit}}">
        </x-form.per-page>
    </div>
</div>

<x-renderer.table showNo="true" :columns=" $columns" :dataSource="$dataSource" />
<br />
<x-modalSettings type="{{$type}}" />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection