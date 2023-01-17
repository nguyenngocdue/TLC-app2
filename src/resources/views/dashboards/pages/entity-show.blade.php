@extends('layouts.app')
@section('content')
{{-- @dd($dataSource) --}}
<div class=" mb-8 bg-white border px-3 py-3">
    @foreach($props as $prop)
    @php
    $control = $prop['control'];
    @endphp
    @if($control !== "z_divider")
    <x-renderer.description-group control={{$control}} :prop="$prop" :items="$dataSource" />
    @endif
    @endforeach
</div>
@endsection
