@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')
@php
        $treeType = Str::replace(' ', '', Str::headline($type));
@endphp

<div class="px-4 mt-2 bg-body">
        <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
        <x-elapse title="ViewAllController: "/> 
        
        <div class="py-2" />
        <x-renderer.view-all.view-all-type-selector type="{{$type}}" viewType="tree-explorer-view" />      
        
        {{-- <x-renderer.view-all-tree-type.{{$treeType}} type="{{$type}}"/> --}}
        @switch($type)
        @case('pj_tasks')
                <x-renderer.view-all-tree-type.Department2Discipline 
                        {{-- type="{{$type}}" --}}
                        />
                @break
        @case('pp_procedure_policies')
                <x-renderer.view-all-tree-type.PpProcedurePolicy 
                        {{-- type="{{$type}}" --}}
                        />
                @break
        @default
                Unknown how to render tree for {{$type}}, search file entity-view-all-tree-explorer.blade
                @break        
        @endswitch
</div>
@endsection
