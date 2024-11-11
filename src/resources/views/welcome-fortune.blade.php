@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<script>
const table = new EditableTable3({ 
    columns: [],
    dataSource: [],
 });
</script>

<x-renderer.table3
    :dataSource="$dataSource"
    :columns="$columns"
    showPaginationTop="true"
    showNo=1

    ></x-renderer.table>

@endsection 