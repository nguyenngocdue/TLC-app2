@extends('layouts.app')
@section('title', 'Manage Properties')

@section('content')
<form action="{{$route}}" method="POST">
    @csrf
    <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo=true maxH=32></x-renderer.table>
    <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
</form>
<br />
<hr />
<x-form.create-new action="{{$route}}/create"/>
<br />
<br />
<br />
@endsection