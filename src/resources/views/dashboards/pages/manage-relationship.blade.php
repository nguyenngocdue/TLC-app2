@extends('layouts.app')
@section('title', 'Manage Json')

@section('content')
<x-navigation.pill />
<form action="{{$route}}" method="POST">
    @csrf
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" />
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
@endsection