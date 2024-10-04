@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')

@section('content')
@once
    <script src="{{ asset('js/my-org-chart.js') }}"></script>
@endonce

<div class="px-4">
    <div class='p-2 no-print'>
        <x-renderer.button href="/my-org-chart?mode=standard" type="{{$viewSettings['org_chart_mode']=='standard' ? 'secondary' : ''}}">Standard Mode</x-renderer.button>
        <x-renderer.button href="/my-org-chart?mode=advanced" type="{{$viewSettings['org_chart_mode']=='advanced' ? 'secondary' : ''}}">Advanced Mode</x-renderer.button>
        <x-renderer.button href="/my-org-chart?mode=external" type="{{$viewSettings['org_chart_mode']=='external' ? 'secondary' : ''}}">External Mode</x-renderer.button>
    </div>

    @switch($viewSettings['org_chart_mode'])
    @case('standard')
        <x-renderer.org-chart.org-chart-renderer 
            id="0" departmentId='2' isPrintMode=1 zoomToFit=1
            :options="['loadResigned' => [0], 'loadWorker' => [], 'loadOnlyBod' => [1],]" 
            />
        @foreach($departments as $department)
            <x-renderer.org-chart.org-chart-renderer 
                id="{{$department->id}}" 
                departmentId='{{$department->id}}' 
                :departments="$departments" 
                :options="$printOptions" 
                isPrintMode=1
                zoomToFit=1
                />
        @endforeach
    @break
    @case('advanced')
        <x-renderer.org-chart.org-chart-toolbar :showOptions="$showOptions"/>
        <div class="flex items-center justify-center no-print">
            <x-controls.text2 type="search" class="w-[550px] mr-1 my-2" name="mySearch_0"
            placeholder="Press ENTER to search, and Press SPACE to pan to the next result"
            value="" onkeypress="if (event.keyCode === 13) searchDiagram(0)" />
            <x-renderer.button type="secondary" onClick="searchDiagram(0)" class="w-20" >Search</x-renderer.button>
        </div>
        <x-renderer.org-chart.org-chart-renderer id="0" departmentId="{{$showOptions['department']??0}}" :options="$options" isPrintMode="{{false}}" zoomToFit="{{false}}"/>
    @break
    @case('external')
        <x-renderer.org-chart.org-chart-renderer-external id=0/>
    @break
    @default
        Mode not found [{{$viewSettings['org_chart_mode']}}]
    @break
    @endswitch
</div>
@endsection
