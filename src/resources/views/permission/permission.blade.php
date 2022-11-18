@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<x-renderer.table showNo=true :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
@endsection