@extends('layouts.app')

@section('topTitle', $appName)
@section('title', "Overview of ". $project->name )

@section('content')
<div class="p-5 bg-body">
    <x-elapse title="Bootstrap: "/>
    
    @if($table == 'projects')
        <div class="py-2" />
        <x-renderer.project.project-team table="{{$table}}" id="{{$projectId}}" />
    @endif

    <div class="py-2" />
    <x-renderer.project.project-overview table="{{$table}}" id="{{$projectId}}" />

    <div class="py-2" />
    <x-dashboards.my-view-groups table="{{$table}}" projectId="{{$projectId}}" />
</div>
@endsection
