@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<div>
    <x-renderer.card class="m-4 bg-white p-0">
        <x-renderer.tab-pane activeTab="new" :dataSource="$dataSource"></x-renderer.tab-pane>
    </x-renderer.card>
</div>
@endsection
