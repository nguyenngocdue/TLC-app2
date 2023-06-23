@extends('layouts.app')
@section('topTitle', 'Database Diagrams')
@section('title', '')

@section('content')
    {{-- <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo={{true}}  /> --}}
    @php
        foreach($tables as $tableName => $table){
            echo $tableName;
            echo Blade::render('<x-renderer.table :columns="$columns" :dataSource="$dataSource"></x-renderer.table>',[
                'columns' =>$columns,
                'dataSource' =>$table,
            ]);
        }
    @endphp

@endsection
