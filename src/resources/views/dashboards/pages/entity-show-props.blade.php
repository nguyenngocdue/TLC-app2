@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
<style>
    html {
        -webkit-print-color-adjust: exact;
    }
</style>
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
<x-print.print-props
    id="{{$id}}"
    type="{{$type}}"
    modelPath="{{$modelPath}}"
    trashed="{{$trashed}}"
    layout="{{$layout}}"
    topTitle="{{$topTitle}}"
    :item="$item"
    />
<x-print.printed-time-zone />
@endsection
