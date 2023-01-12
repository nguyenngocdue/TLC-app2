@extends('layouts.app')

@section('title', 'Manage Relationships')

@section('content')
<form action="{{ route($type . '_rel.store') }}" method="POST">
    @csrf
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" />
    <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
</form>
@endsection