@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php 
$currentDate = str_replace('Nguyen', 'Mr.', 'Nguyen Ngoc Due');
$fromDate = new DateTime('2024-09-18 08:58:59');
$fromDate = $fromDate->format('Y-m-d');

@endphp
<x-renderer.heading level=1 class="italic text-center">Hello {{$currentDate}}</x-renderer.heading>
<x-renderer.heading level=1 class="italic text-center">Salary Period: from {{$fromDate}}</x-renderer.heading>
@endsection
