@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php 
$currentDate = str_replace('Nguyen', 'Mr.', 'Nguyen Ngoc Due');
$fromDate = new DateTime('2024-09-18 08:58:59');
$fromDate = $fromDate->format('Y-m-d');

@endphp
@php
$entityType = '1a';
@endphp

        <x-renderer.card title="Colspan and Rowspan">
            <x-renderer.table :columns="$tableSpanColumns" :dataSource="$tableSpanDataSource" />
        </x-renderer.card>


         
@endsection
