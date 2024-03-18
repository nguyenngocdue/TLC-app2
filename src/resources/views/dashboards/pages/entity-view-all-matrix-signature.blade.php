@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')
<div class="px-4 mt-2 bg-body">
        <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
        <x-elapse title="ViewAllController: "/> 
        
        <div class="py-2" />
        <x-renderer.view-all.view-all-type-selector type="{{$type}}" viewType="matrix-signature-view" />

        {{-- <div class="py-2" /> --}}
        <x-renderer.view-all.view-all-type-matrix type="{{$type}}" view="signature" />
</div>
@endsection
