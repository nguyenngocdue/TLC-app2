@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="m-4">
    <x-renderer.conqa_archive.conqa_archive :item="$item" />
</div>

@endsection