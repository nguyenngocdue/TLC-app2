@extends('layouts.app')

@section('topTitle', Str::appTitle($type))
@section('title', $title)

@section('content')
<form action="{{$route}}" method="post">
    @csrf
    <x-renderer.table showNo=true groupBy="name" :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
<br />
<hr />
<x-form.create-new action="{{$route}}/create" />
<br />
<br />
<br />
@endsection