@extends('layouts.app')

@section('title', 'Manage Relationships')

@section('content')
<form action="{{ route($type . '_mngprop.store') }}" method="POST">
    @csrf
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" />
    <x-controls.button>Update</x-controls.button>
</form>
@endsection