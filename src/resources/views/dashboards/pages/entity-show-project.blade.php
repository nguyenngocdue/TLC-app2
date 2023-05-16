@extends('layouts.app')

@section('topTitle', $appName)
@section('title', "Overview of ".$project->name )

@section('content')
<div class="p-5">
    <x-elapse title="Bootstrap: "/>
    @if($table == 'projects')
    <x-renderer.project.project-team table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-team>
    @endif
    <x-renderer.project.project-overview table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-overview>
    <x-dashboards.my-view-groups projectId="{{$projectId}}" />
    {{-- <x-dashboards.widget-groups projectId="5"/> --}}
    {{-- <x-dashboards.widget-groups subProjectId="21"/> --}}
    {{-- <x-dashboards.widget-groups subProjectId="82"/> --}}
</div>
@endsection
