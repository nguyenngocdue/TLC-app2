@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4 bg-body">
    <x-navigation.pill />
    <div class="grid grid-cols-2 gap-5">
        <form action="{{$route}}" method="POST">
            @csrf
            <x-renderer.table :columns="$columns0" :dataSource="$dataSource0" showNo=true maxH={{512}}></x-renderer.table>
        </form>
        <form action="{{$route}}" method="POST">
            @csrf
            <x-renderer.table :columns="$columns1" :dataSource="$dataSource1" showNoR=true maxH={{512}} groupBy="title" 
            header="<a href='{{$routeManage}}'>Manage Statuses</a>"
            footer="<a href='{{$routeManage}}'>Manage Statuses</a>"
            ></x-renderer.table>
        </form>
    </div>
</div>
@endsection