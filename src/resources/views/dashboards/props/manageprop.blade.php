@extends('layouts.app')

@section('title', 'Manage Props')

@section('content')
<form action="{{ route($type . '_mngprop.store') }}" method="POST">
    @csrf
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" />
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
@endsection