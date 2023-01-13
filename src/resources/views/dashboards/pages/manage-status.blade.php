@extends('layouts.app')
@section('title', $title)

@section('content')
<div class="grid grid-cols-2 gap-5">
    <form action="{{$route}}" method="POST">
        @csrf
        <x-renderer.table :columns="$columns0" :dataSource="$dataSource0" showNo=true></x-renderer.table>
    </form>
    <form action="{{$route}}" method="POST">
        @csrf
        <x-renderer.table :columns="$columns1" :dataSource="$dataSource1" showNoR=true groupBy="title" 
        header="<a href='{{$route}}'>Manage Statuses</a>"
        footer="<a href='{{$route}}'>Manage Statuses</a>"
        ></x-renderer.table>
    </form>
</div>
@endsection