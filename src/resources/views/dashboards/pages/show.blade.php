@extends('layouts.app')
@section('content')
{{-- @dd($dataSource) --}}
<div class=" mb-8 bg-white border px-3 py-3">
    @foreach($dataSource as $key => $prop)
    @php
    // $props = $prop['children'];
    // $heading = $prop['label'];
    // $colSpan = $prop['col_span'];
    // $newLine = $prop['new_line'] ?? false;
    $control = $prop['control'];
    @endphp
    @if($control !== "z_divider")
    <x-renderer.descriptions control={{$control}} :items="$prop" :dataContent="$dataContent" />
    @endif
    @endforeach
</div>
@endsection
