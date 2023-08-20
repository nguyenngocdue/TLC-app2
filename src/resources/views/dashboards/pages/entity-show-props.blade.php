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
    <x-print.print-props type="{{$type}}" modelPath="{{$modelPath}}" trashed="{{$trashed}}" id="{{$id}}" layout="{{$layout}}" />
    <x-print.printed-time-zone />
@endsection
