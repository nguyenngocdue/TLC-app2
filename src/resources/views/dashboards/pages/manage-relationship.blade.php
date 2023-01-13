@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{$route}}" method="POST">
    @csrf
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" />
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
@endsection