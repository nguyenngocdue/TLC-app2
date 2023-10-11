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
    <x-renderer.org-chart.org-chart-renderer id="0" :dataSource="$dataSource"/>
    <x-renderer.org-chart.org-chart-renderer id="1" :dataSource="$dataSource"/>
   
</div>
@endsection
