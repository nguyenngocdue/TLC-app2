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

    {{-- <div class="grid grid-row-1 w-full">
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
 --}}
<div class="relative group inline-block">
    <a class="group-hover:underline" target="_blank" href="#" title="id :">
        <span class='px-1'>AAAAA</span>
        <span class="text-red-400" title="required">*</span>
    </a>

    <!-- The nodes that appear on hover -->
    <div class="absolute hidden group-hover:flex left-full top-0 ml-2 space-x-2">
        <!-- Node 1 -->
        <div class="bg-gray-200 p-2 rounded shadow-lg">
            Node 1 Content
        </div>

        <!-- Node 2 -->
        <div class="bg-gray-200 p-2 rounded shadow-lg">
            Node 2 Content
        </div>
    </div>
</div>


@endsection
