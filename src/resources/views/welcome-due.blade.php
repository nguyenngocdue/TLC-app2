@extends('layouts.app')
@section('content')
@php
       
@endphp
    <x-renderer.report.pivot-table key="{{$key}}" :dataSource="$dataSource"/>
@endsection

