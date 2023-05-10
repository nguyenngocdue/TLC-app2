@extends('layouts.app')
@section('content')

<x-renderer.table :columns="$columns" :dataSource="$dataSource" />

{{-- <div>
    <x-renderer.project.project-team></x-renderer.project.project-team>
    <x-renderer.project.project-overview></x-renderer.project.project-overview>
</div> --}}
@endsection
