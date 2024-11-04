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

    <iframe 
        src="{{ url('/dashboard/rp_reports/65') }}" 
        style="width:100%; height: 800px; border:none;"
        title="Embedded Report Page">
    </iframe>



                
@endsection
