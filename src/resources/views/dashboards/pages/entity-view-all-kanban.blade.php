@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')
<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    
    <x-renderer.kanban.page />
</div>
@endsection
