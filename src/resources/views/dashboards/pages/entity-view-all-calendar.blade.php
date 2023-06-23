@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    <x-renderer.view-all-type-selector type="{{$type}}" viewType="calendar-view" />
    
    <x-renderer.view-all-type-calendar type="{{$type}}" typeModel="{{$typeModel}}" :dataSource="$dataSource"/>
</div>
{{-- <script src="{{ asset('js/renderprop.js') }}"></script> --}}
@endsection
