@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
{{-- @dd($dataSource) --}}
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1100px] items-center bor1der bg-white box-border p-8">
        <x-renderer.letter-head showId={{$showId}} type={{$type}} :dataSource="$dataSource" />
            @foreach($propsTree as $propTree)
                <x-renderer.description-group type={{$type}} modelPath={{$modelPath}} :propTree="$propTree" :dataSource="$dataSource" />
            @endforeach

    </div>
</div>

@endsection
