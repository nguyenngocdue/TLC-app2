@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')

@section('content')
@once
    <script src="{{ asset('js/my-org-chart.js') }}"></script>
@endonce

<div >
    @if($isAdmin)
    <div class='p-2 no-print'>
        <x-renderer.button href="/my-org-chart" type="{{$viewSettings['approval_view'] ? '' : 'secondary'}}">Position ORG Chart </x-renderer.button>
        <x-renderer.button href="/my-org-chart?approval-tree=true" type="{{$viewSettings['approval_view'] ? 'secondary' : ''}}">Approval Tree</x-renderer.button>
    </div>
    @endif

    <x-renderer.org-chart.org-chart-toolbar :showOptions="$showOptions"/>

    @if(App::isLocal())
        @foreach($departments as $department)
            <x-renderer.org-chart.org-chart-renderer id="{{$department->id}}" :departments="$departments" departmentId='{{$department->id}}' :options="$options" isApprovalView="{{$viewSettings['approval_view']}}"/>
        @endforeach
    @else
        <x-renderer.org-chart.org-chart-renderer id="0" departmentId="{{$showOptions['department']}}" :options="$options" isApprovalView="{{$viewSettings['approval_view']}}"/>
    @endif
</div>
@endsection
