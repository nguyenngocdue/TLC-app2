@extends('layouts.app')
@section('title', 'Permissions #'.$id)
@section('content')


<form action="{{route('permissions2.update', $id)}}" method="post">
    @csrf
    @method('PUT')
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" />
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>

@endsection