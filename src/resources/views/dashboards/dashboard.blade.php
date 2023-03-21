@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4">
    <div class="mb-8 grid gap-6 2xl:grid-cols-4 xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1">
        @foreach($allWidgets as $widget)
        @php
            $title = $widget['title_a']." - ".$widget['title_b'];
        @endphp
        <x-renderer.card title="{{$title}}">
            <x-renderer.report.widget title="Total {{$widget['title_a']}}" figure="{{$widget['meta']['max']}}"/>
            <br/>
            <x-renderer.report.chart key="{{md5($title)}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" />
        </x-renderer.card>
        @endforeach
    </div>

{{-- 
    <!-- CTA -->
    <a class="focus:shadow-outline-purple mb-8 flex items-center justify-between rounded-lg bg-purple-600 p-4 text-sm font-semibold text-purple-100 shadow-md focus:outline-none" href="#">
        <div class="flex items-center">
            <i class="mr-2 fa-duotone fa-stars"></i>   
            <span>This is a dashboard divider</span>
        </div>
        <span>View more &RightArrow;</span>
    </a>
    <!-- Cards -->
    <div class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card -->
        <div class="shadow-xs flex items-center rounded-lg bg-white p-4 dark:bg-gray-800">
            <div class="mr-4 rounded-full bg-orange-100 p-3 text-orange-500 dark:bg-orange-500 dark:text-orange-100">
                <i class="fa-duotone fa-users"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                    Total clients
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    6389
                </p>
            </div>
        </div>
        <!-- Card -->
        <div class="shadow-xs flex items-center rounded-lg bg-white p-4 dark:bg-gray-800">
            <div class="mr-4 rounded-full bg-green-100 p-3 text-green-500 dark:bg-green-500 dark:text-green-100">
                <i class="fa-duotone fa-users"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                    Account balance
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    $ 46,760.89
                </p>
            </div>
        </div>
        <!-- Card -->
        <div class="shadow-xs flex items-center rounded-lg bg-white p-4 dark:bg-gray-800">
            <div class="mr-4 rounded-full bg-blue-100 p-3 text-blue-500 dark:bg-blue-500 dark:text-blue-100">
                <i class="fa-duotone fa-users"></i>            
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                    New sales
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    376
                </p>
            </div>
        </div>
        <!-- Card -->
        <div class="shadow-xs flex items-center rounded-lg bg-white p-4 dark:bg-gray-800">
            <div class="mr-4 rounded-full bg-teal-100 p-3 text-teal-500 dark:bg-teal-500 dark:text-teal-100">
                <i class="fa-duotone fa-users"></i>            
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                    Pending contacts
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    35
                </p>
            </div>
        </div>
    </div> --}}
</div>
@endsection