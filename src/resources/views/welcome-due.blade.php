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
<!-- Blade Template -->

<!-- Blade Template -->
<!-- Blade Template -->
<div class="relative inline-block group">
    <span class="text-blue-500 cursor-pointer">Hover over me</span>

    <!-- Hidden nodes, shown on hover -->
    <div class="absolute left-0 hidden group-hover:block space-y-2 bg-white p-2 rounded shadow-lg z-10">
        <a href="https://www.google.com" target="_blank" class="flex items-center bg-blue-100 hover:bg-blue-200 px-4 py-2 w-full text-left rounded text-blue-700">
            <i class="fas fa-link mr-2"></i> <!-- Font Awesome icon -->
            <span class="truncate">Node 111111111</span>
        </a>
        <a href="https://www.google.com" target="_blank" class="flex items-center bg-green-100 hover:bg-green-200 px-4 py-2 w-full text-left rounded text-green-700">
            <i class="fas fa-link mr-2"></i> <!-- Font Awesome icon -->
            <span class="truncate">Node 2111111111</span>
        </a>
    </div>
</div>

<!-- Include Font Awesome -->






@endsection
