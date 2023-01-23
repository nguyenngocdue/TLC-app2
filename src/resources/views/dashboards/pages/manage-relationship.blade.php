@extends('layouts.app')
@section('title', $title)

@section('content')
<x-navigation.pill />
<form action="{{$route}}" method="POST">
    @csrf
    <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" maxH=32/>
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
@endsection