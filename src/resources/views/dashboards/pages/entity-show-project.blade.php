@extends('layouts.app')

@section('topTitle', 'Projects')
@section('title', "Overview of ".$project->name )

@section('content')
<div class="p-5">
    <x-elapse title="Bootstrap: "/>
    <x-renderer.project.project-team table="projects" id="{{$projectId}}"></x-renderer.project.project-team>
    <x-renderer.project.project-overview id="{{$projectId}}"></x-renderer.project.project-overview>
    <x-dashboards.my-view-groups projectId="{{$projectId}}" />
    {{-- <x-dashboards.widget-groups projectId="5"/> --}}
    {{-- <x-dashboards.widget-groups subProjectId="21"/> --}}
    {{-- <x-dashboards.widget-groups subProjectId="82"/> --}}
</div>
@endsection
