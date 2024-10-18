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
<div id="{{$entityType}}">Hello</div>
<x-renderer.heading level=1 class="italic text-center">Hello {{$currentDate}}</x-renderer.heading>
<x-renderer.heading level=1 class="italic text-center">Salary Period: from {{$fromDate}}</x-renderer.heading>
<x-renderer.button htmlType="button" click="resetFilter()" type="secondary"><i  class="fa-sharp fa-solid fa-circle-xmark pr-1"></i> Reset</x-renderer.button>
{{-- Rest Time Range --}}
<script>
    function resetFilter() {
      $('[id="' + "{{ $entityType }}" + '"]').append(
            '<input type="" name="form_type" value="resetParamsReport">')
    }
</script>


                
@endsection
