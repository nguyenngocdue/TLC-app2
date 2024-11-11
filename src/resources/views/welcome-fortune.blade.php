@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<x-renderer.table3
    :columns="$columns"
    :dataSource="$dataSource"
    {{-- showPaginationTop={{!true}} --}}
    {{-- showPaginationBottom={{!true}} --}}
    showNo=1

    header="Table Header"
    footer="Table Footer"

    ></x-renderer.table>

@endsection 