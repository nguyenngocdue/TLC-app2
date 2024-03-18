@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')
<div class="px-4 mt-2 bg-body">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    
    <div class="py-2" />
    <x-renderer.view-all.view-all-type-selector type="{{$type}}" viewType="calendar-view" />
    
    <div class="py-2" />
    <x-renderer.view-all.view-all-type-calendar type="{{$type}}" typeModel="{{$typeModel}}" :dataSource="$dataSource"/>
</div>
{{-- <script src="{{ asset('js/renderprop.js') }}"></script> --}}
@endsection
