@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
    @php
        $dataLine = [
            'user_id' => 362,
            'user_name' => 'NGỌC DUỆ',
        ];

        $routeStr = 'route("users.edit", {{ $user_id }})';

    @endphp
    {{-- 
    <x-renderer.hyper-link cell="hello">DEF</x-renderer.hyper-link>

    <x-renderer.id type="users">150</x-renderer.id>

    <x-renderer.id type="users">ABC</x-renderer.id>

    <x-renderer.status>in progress</x-renderer.status>

    <x-renderer.id_name_link :dataLine="$dataLine" routeStr="{{ $routeStr }}">Id Name</x-renderer.status>

    <x-renderer.id_status_link :dataSource="$ids" showTitle=1>150</x-renderer.id_status_link> --}}

    <div class="grid grid-row-1 w-full">
        <div class="grid grid-cols-12 gap-4 items-baseline">
            <div id="" class="col-span-2">
                <span class='px-1'>Project</span>
                <x-renderer.report2.filter-report-item />
            </div>
            <div id="" class="col-span-2">
                <span class='px-1'>Project</span>
                <x-renderer.report2.filter-report-item-listener />
            </div>
        </div>
    </div>


@endsection
