@extends('layouts.app')
@section('title', 'Manage Statuses')

@section('content')
<form method="post">
    @csrf
    <x-renderer.table showNo=true :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
    <x-controls.button>Update</x-controls.button>
</form>
@endsection