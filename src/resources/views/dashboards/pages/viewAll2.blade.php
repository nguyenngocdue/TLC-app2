@extends('layouts.app')

@section('title', App\Utils\Support\CurrentRoute::getTypePluralPretty())

@section('content')

@isset($messages)
<div class="m-auto mt-[15%] mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4 dark:bg-yellow-200">
    @foreach ($messages as $message)
    <p><a class="text-blue-500" href="{{ route($message['href']) }}">{{ $message['title'] }}</a> is missing.
    </p>
    @endforeach
</div>
@endisset

@empty($messages)
<div class="grid grid-cols-2 gap-5">
    <x-form.search action="{{ route($type . '_viewall.index') }}">
    </x-form.search>
    <div class="grid justify-items-end">
        <x-form.per-page type="{{$type}}" action="{{ route('updateUserSettings') }}" page-limit="{{$pageLimit}}">
        </x-form.per-page>
    </div>
</div>

<x-renderer.table showNo="true" :columns=" $columns" :dataSource="$dataSource" />
<br />
@endempty

<x-modalSettings type="{{$type}}" />
<script src="{{ asset('js/renderprop.js') }}"></script>

@endsection