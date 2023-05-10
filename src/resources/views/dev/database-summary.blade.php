@extends('layouts.app')
@section('topTitle', 'Database Summary')
@section('title', '')

@section('content')
    <x-renderer.table :columns="$columns" :dataSource="$dataSource"  />
@endsection
