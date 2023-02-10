@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
{{-- @dd($dataSource) --}}
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1300px] items-center border bg-white box-border">
        <x-renderer.letter-head />
        @dump($props)
        @dump($dataSource)
        @foreach($props as $prop)
        @php
        $control = $prop['control'];
        @endphp
        @if($control !== "z_divider")
        @dump()
        <x-renderer.description-group control={{$control}} :prop="$prop" :items="$dataSource" />
        @endif
        @endforeach
    </div>
    
</div>
@endsection
