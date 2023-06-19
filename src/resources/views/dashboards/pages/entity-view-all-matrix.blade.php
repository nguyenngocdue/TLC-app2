@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title' ) View All <sub>{!! $title !!}</sub> @endsection

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    <x-renderer.view-all-type-selector type="{{$type}}" viewType="matrix-view" />
    <x-renderer.view-all-type-matrix type="{{$type}}" typeModel="{{$typeModel}}" viewportDate="{{$viewportDate}}" />
</div>
{{-- <script src="{{ asset('js/renderprop.js') }}"></script> --}}
@endsection
