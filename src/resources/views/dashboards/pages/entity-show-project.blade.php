@extends('layouts.app')

@section('topTitle', 'Projects')
@section('title', "Overview of ".$project->name )

@section('content')
<div class="p-5">
    <x-renderer.project.project-team table="projects" id="{{$projectId}}"></x-renderer.project.project-team>
    <x-renderer.project.project-overview id="{{$projectId}}"></x-renderer.project.project-overview>
    <x-dashboards.my-view-groups projectId="{{$projectId}}" />
</div>
@endsection
