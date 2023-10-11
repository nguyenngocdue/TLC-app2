@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')

@section('content')
@once
    <script src="{{ asset('js/my-org-chart.js') }}"></script>
@endonce

<div class="px-4">
    @if($isAdmin)
    {{-- <div class='p-2 no-print'>
        <x-renderer.button href="/my-org-chart" type="{{$viewSettings['approval_view'] ? '' : 'secondary'}}">Position ORG Chart </x-renderer.button>
        <x-renderer.button href="/my-org-chart?approval-tree=true" type="{{$viewSettings['approval_view'] ? 'secondary' : ''}}">Approval Tree</x-renderer.button>
    </div> --}}
    @endif
    
    <div class='p-2 no-print'>
        <x-renderer.button href="/my-org-chart" type="{{$viewSettings['advanced_mode'] ? '' : 'secondary'}}">Standard Mode</x-renderer.button>
        <x-renderer.button href="/my-org-chart?advanced-mode=true" type="{{$viewSettings['advanced_mode'] ? 'secondary' : ''}}">Advanced Mode</x-renderer.button>
    </div>

    
    @if(!$viewSettings['advanced_mode'])
        <x-renderer.org-chart.org-chart-renderer id="0" departmentId='2' :options="$bodOptions" isPrintMode="{{true}}" isApprovalView="{{$viewSettings['approval_view']}}" zoomToFit="{{true}}"/>
        @foreach($departments as $department)
            <x-renderer.org-chart.org-chart-renderer id="{{$department->id}}" :departments="$departments" departmentId='{{$department->id}}' :options="$printOptions" isPrintMode="{{true}}" isApprovalView="{{$viewSettings['approval_view']}}" zoomToFit="{{true}}"/>
        @endforeach
    @else
        <x-renderer.org-chart.org-chart-toolbar :showOptions="$showOptions"/>
        <div class="flex items-center justify-center no-print">
            <x-controls.text2 type="search" class="w-[550px] mr-1 my-2" name="mySearch_0"
            placeholder="Press ENTER to search, and Press SPACE to pan to the next result"
            value="" onkeypress="if (event.keyCode === 13) searchDiagram(0)" />
            <x-renderer.button type="secondary" onClick="searchDiagram(0)" class="w-20" >Search</x-renderer.button>
        </div>
        <x-renderer.org-chart.org-chart-renderer id="0" departmentId="{{$showOptions['department']??0}}" :options="$options" isPrintMode="{{false}}" isApprovalView="{{$viewSettings['approval_view']}}" zoomToFit="{{false}}"/>
    @endif
</div>
@endsection
