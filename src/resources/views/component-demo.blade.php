@extends('layouts.applean')
@section('topTitle', 'Component Demo')

@section('content')

@php
    $activeClass = "bg-white border-t border-r border-l -mb-px";
@endphp

<x-renderer.heading level=3 align='center'><a href="/components">Components</a></x-renderer.heading>

<x-renderer.card class="mx-5">
    <!-- Tabs -->
    <ul id="tabs-e07ff0dbf9a2afd616aca8e7a85921e2" class="inline-flex pt-2 px-1 w-full border-b">
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t "><a href="#static">Static</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t "><a href="#data_display">Data Display</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t "><a href="#data_entry">Data Entry</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t {{$activeClass}}"><a href="#attachments">Attachments / Comments</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t "><a href="#editable_tables">Editable Tables</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t "><a href="#navigation">Navigation</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t "><a href="#feedbacks">Feedbacks</a></li>
    </ul>

    <!-- Tab Contents -->
    <div id="tab-contents-e07ff0dbf9a2afd616aca8e7a85921e2">
        <div id="static" class="p-4 hidden">
            {{-- <x-demo.demo-static :tagColumns="$tagColumns" :tagDataSource="$tagDataSource" :gridDataSource="$gridDataSource" /> --}}
        </div>
        <div id="data_display" class="p-4 hidden">
            {{-- <x-demo.demo-data-display :tableColumns="$tableColumns" :tableDataSource="$tableDataSource" :tableDataHeader="$tableDataHeader" /> --}}
        </div>
        <div id="data_entry" class="p-4 hidden">
            {{-- <x-demo.demo-data-entry :dropdownCell="$dropdownCell" /> --}}
        </div>
        <div id="attachments" class="p-4 ">
            <x-demo.demo-attachment :attachmentData="$attachmentData" :attachmentData2="$attachmentData2" :dataComment="$dataComment" />
        </div>
        <div id="editable_tables" class="p-4 hidden">
            {{-- <x-demo.demo-data-table :tableEditableColumns="$tableEditableColumns" :tableDataSource="$tableDataSource" /> --}}
        </div>
        <div id="navigation" class="p-4 hidden">
            {{-- <x-demo.demo-navigation :tabData1="$tabData1" :tabData2="$tabData2" /> --}}
        </div>
        <div id="feedbacks" class="p-4 hidden">
            <x-demo.demo-feedback />
        </div>
    </div>
</x-renderer.card>
<script>
    initTab('e07ff0dbf9a2afd616aca8e7a85921e2');

</script>
@endsection
