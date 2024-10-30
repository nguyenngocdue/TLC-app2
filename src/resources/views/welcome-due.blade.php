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
<html :class="{ 'dark': isDark }" x-data="alpineData()" x-ref="alpineRef"  lang="en">


{{-- Rest Time Range --}}
<script>


   console.log(13, document.querySelector('[x-ref="alpineRef"]'))

</script>


                
@endsection
