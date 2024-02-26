@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')

<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
@foreach($dataSource as $item)
    <x-print.print-props
    id="{{$item->id}}"
    type="{{$type}}"
    modelPath="{{$modelPath}}"
    trashed="{{$trashed}}"
    layout="{{$layout}}"
    topTitle="{{$topTitle}}"
    :item="$item"
    />
    <x-renderer.page-break />
@endforeach
<x-print.printed-time-zone />
@endsection
