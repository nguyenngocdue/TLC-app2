@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', 'Manage Properties')

@section('content')
<div class="px-4">
    <form action="{{$route}}" method="POST">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo=true maxH=32></x-renderer.table>
        <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
        << Click twice when change Field ID to update Field Name in JSON
    </form>
    <br />
    <hr />
    <x-form.create-new action="{{$route}}/create"/>
</div>
@endsection