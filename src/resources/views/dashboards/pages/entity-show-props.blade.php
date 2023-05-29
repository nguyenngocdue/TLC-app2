@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
@php
        switch ($valueOptionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[1000px]';
            break;
            case 'portrait':
            default:
                $layout = 'w-[1000px] min-h-[1355px]';
                break;
        }
@endphp
<div class="flex justify-center">
    <div class="{{$layout}} items-center bor1der bg-white box-border p-8">
        <x-print.letter-head5 showId={{$showId}} type={{$type}} :dataSource="$dataSource" />
        @foreach($propsTree as $propTree)
        <x-print.description-group5 type={{$type}} modelPath={{$modelPath}} :propTree="$propTree" :dataSource="$dataSource" />
        @endforeach

    </div>
</div>
@endsection
