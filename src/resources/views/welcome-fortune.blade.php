@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<x-renderer.table
    :dataSource="$dataSource"
    :columns="$columns"
    showPaginationTop="true"
    showNo=1

    ></x-renderer.table>

@endsection 