@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')
<div class="px-4 mt-2">
        <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
        <x-elapse title="ViewAllController: "/> 
        <x-renderer.view-all.view-all-type-selector type="{{$type}}" viewType="matrix-signature-view" />
        <x-renderer.view-all.view-all-type-matrix type="{{$type}}" view="signature" />
</div>
@endsection
