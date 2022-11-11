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
    <x-pages.search-box type="{{$type}}" search="{{$search}}"></x-pages.search-box>
    <div class="grid justify-items-end">
        <x-pages.goto-box type="{{$type}}" page-limit="{{$pageLimit}}"></x-pages.goto-box>
    </div>
</div>

<x-renderer.table :columns="$columns" :dataSource="$dataSource" />
@endempty

<x-modalsetting :type="$type" />
<script src="{{ asset('js/renderprop.js') }}"></script>

@endsection