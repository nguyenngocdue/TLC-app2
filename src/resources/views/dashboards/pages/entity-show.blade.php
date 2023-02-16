@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
{{-- @dd($dataSource) --}}
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1300px] items-center border bg-white box-border">
        <x-renderer.letter-head showId={{$showId}} type={{$type}} />
            @foreach($propsTree as $propTree)
                <x-renderer.description-group :propTree="$propTree" :dataSource="$dataSource" />
            @endforeach

    </div>
    
</div>

@endsection
