@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<div>
    <x-renderer.project.project-team></x-renderer.project.project-team>
    <x-renderer.project.project-overview></x-renderer.project.project-overview>
</div>
@endsection
