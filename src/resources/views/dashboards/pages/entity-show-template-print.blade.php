@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
<div class="px-4 mt-2">
    @foreach($dataSource as $id => $value)
    <x-print.print-props type="{{$type}}" printMode="template" modelPath="{{$modelPath}}" trashed="{{$trashed}}" id="{{$id}}" layout="{{$layout}}" numberOfEmptyLines="{{$numberOfEmptyLines}}" />
    <x-renderer.page-break />
    @endforeach
    <x-print.printed-time-zone />
</div>
@endsection
