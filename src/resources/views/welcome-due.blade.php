@extends('layouts.app')
@section('content')
    <x-renderer.report.pivot-table key="{{$key}}" :dataSource="$dataSource"/>
@endsection

