@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script>

<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-2">
            <x-renderer.kanban.pages :page="$page" />
        </div>
        <div class="col-span-10">
            <x-renderer.card title="{{$page->name}}">
                <x-renderer.kanban.page :page="$page"/>
            </x-renderer.card>
        </div>
    </div>
</div>
@endsection
