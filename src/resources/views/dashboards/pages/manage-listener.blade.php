@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{$route}}" method="POST">
    @csrf
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo=true></x-renderer.table>
    <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
</form>
<br />
<hr />
<x-form.create-new action="{{$route}}/create"/>
<br />
<br />
<br />
@endsection