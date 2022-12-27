@extends('layouts.app')
@section('title', 'Manage Statuses')

@section('content')



<div class="grid grid-cols-2 gap-5">
    <form method="post">
        @csrf
        <x-renderer.table :columns="$columns0" :dataSource="$dataSource0" showNo=true></x-renderer.table>
    </form>
    <form method="post">
        @csrf
        <x-renderer.table :columns="$columns1" :dataSource="$dataSource1" showNoR=true groupBy="title" 
        header="<a href='/dashboard/workflow/statuses'>Manage Statuses</a>"
        footer="<a href='/dashboard/workflow/statuses'>Manage Statuses</a>"
        ></x-renderer.table>
    </form>
</div>
@endsection