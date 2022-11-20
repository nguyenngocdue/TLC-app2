@extends('layouts.app')
@section('title', 'Manage Statuses')

@php
$columns1 = [
[
'title' => 'Name',
'dataIndex' => 'name',
'renderer' => 'text',
'editable' => true,
],
[
'title' => 'Action',
'dataIndex' => 'action',
'renderer' => 'button',
'align' => 'center',
],
];
$dataSource1 = [
[
'name' => '',
'action' => 'Create',
]
];
@endphp

@section('content')
<form method="post">
    @csrf
    <x-renderer.table showNo=true groupBy="name" :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
    <x-renderer.button>Update</x-renderer.button>
</form>
<br />
<hr />
<x-renderer.heading>Create New</x-renderer.heading>
<form action="statuses/create" class="grid grid-cols-3">
    @csrf
    <x-renderer.table :columns="$columns1" :dataSource="$dataSource1"></x-renderer.table>
</form>
Pipe is allowed. E.G.: name1|name2|name3|...
<br />
<br />
<br />
@endsection