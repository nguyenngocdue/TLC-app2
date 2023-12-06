@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<x-renderer.table :columns="$columns" :dataSource="$dataSource" tableTrueWidth=1/>

@endsection 