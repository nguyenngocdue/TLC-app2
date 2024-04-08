@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Print" )

@section('content')

THIS IS FOR PRINTING SEQUENCE BASE MATRIX VIEW

{{-- <div class="px-4 mt-2">
    @foreach($dataSource as $id => $value)
    @php
    $item = $modelPath::find($id);
    @endphp
    <x-print.print-props
        id="{{$id}}"
        type="{{$type}}"
        printMode="template"
        modelPath="{{$modelPath}}"
        trashed="{{$trashed}}"
        layout="{{$layout}}"
        topTitle="{{$topTitle}}"
        numberOfEmptyLines="{{$numberOfEmptyLines}}"
        :item="$item"
    />
    <x-renderer.page-break />
    @endforeach
    <x-print.printed-time-zone />
</div> --}}
@endsection
