@extends('layouts.app')

@section('topTitle', $appName)
@section('title', "Overview of ".$project->name )

@section('content')
<div class="p-5">
    <x-elapse title="Bootstrap: "/>
    @if($table == 'projects')
    <x-renderer.project.project-team table="{{$table}}" id="{{$projectId}}" />
    @endif
    <x-renderer.project.project-overview table="{{$table}}" id="{{$projectId}}" />
    <x-dashboards.my-view-groups table="{{$table}}" projectId="{{$projectId}}" />
    {{-- <div class="grid grid-cols-12 gap-3">
        <x-renderer.project.project-overview title="Production Orders by Routings" table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-overview>
        <x-renderer.project.project-overview title="NCRs by Routings" table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-overview>
        <x-renderer.project.project-overview title="NCRs by Disciplines" table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-overview>
        <x-renderer.project.project-overview title="MIRs by Disciplines" table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-overview>
        <x-renderer.project.project-overview title="WIRs by Disciplines" table="{{$table}}" id="{{$projectId}}"></x-renderer.project.project-overview>
    </div> --}}
    <x-dashboards.widget-groups table="{{$table}}" projectId="{{$projectId}}"/>
</div>
@endsection
