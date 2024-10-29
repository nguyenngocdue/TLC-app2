@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Print" )

@section('content')

<div class="block py-4 bg-only-print no-print" role="divider"></div>

@php
    $sheets = $entity->getSheets;
@endphp

<div class="flex1 justify-center bg-only-print print-responsive">
   
    <x-print.insp-chklst.cover-page2 avatar="{{$coverAvatar}}" title="{{$coverTitle}}" :dataSource="$coverDataSource"/>
    <x-renderer.page-break /> 

    <x-print.insp-chklst.insp-chklst-toc :entity="$entity" :sheets="$sheets"/>
    <x-renderer.page-break /> 

    {{-- Tried to load by bundle of sheetIds but will take 4s instead of 3s --}}
    @foreach($sheets as $sheet)
        <x-print.insp-chklst.insp-chklst-page :sheet="$sheet"/>
        <x-renderer.page-break /> 
    @endforeach
</div>


@endsection