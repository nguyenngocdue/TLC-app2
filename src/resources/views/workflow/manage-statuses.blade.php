@extends('layouts.app')
@section('title', 'Manage Statuses')

@section('content')
<form method="post">
    @csrf
    <x-renderer.table showNo=true groupBy="name" :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
<br />
<hr />
<x-form.create-new action="statuses/create" footer="Pipe is allowed. E.G.: name1|name2|name3|..." />
<br />
<br />
<br />
@endsection