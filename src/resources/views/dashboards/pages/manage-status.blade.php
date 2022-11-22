@extends('layouts.app')
@section('title', 'Manage Statuses')

@section('content')
<div class="grid grid-cols-2 gap-5">

    <x-renderer.table :columns="$columns0" :dataSource="$dataSource0"></x-renderer.table>
    <x-renderer.table :columns="$columns1" :dataSource="$dataSource1"></x-renderer.table>
</div>
@endsection