@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php 
$currentDate = str_replace('Nguyen', 'Mr.', 'Nguyen Ngoc Due');
$fromDate = new DateTime('2024-09-18 08:58:59');
$fromDate = $fromDate->format('Y-m-d');

@endphp


<button onclick="showModal()">Show</button>


    {{-- Rest Time Range --}}
    <script>
    
        function showModal() {
            const alpineRef = document.querySelector('[x-ref="alpineRef"]');
            const alpineComponent = alpineRef.__x.$data.toggleModal('modal-report-chart');
        }
    </script>


<x-modals.modal-report-chart modalId="modal-report-chart" />
                
@endsection
