@extends('layouts.app')
@section('title', 'Manage Statuses')

@section('content')
<div class="grid grid-cols-2 gap-5">
    <form method="post">
        @csrf
        <x-renderer.table :columns="$columns0" :dataSource="$dataSource0"></x-renderer.table>
    </form>
    <form method="post">
        @csrf
        <x-renderer.table :columns="$columns1" :dataSource="$dataSource1"></x-renderer.table>
    </form>
</div>
@endsection