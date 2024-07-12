@extends('layouts.app')

@section('topTitle', $appName)
@section('title', $report->name)
@section('subTitle', $report->description)

@section('content')
<div class="p-5 bg-body">
    <div class="border rounded bg-gray-200 p-10 text-center">
        My report renderer is here
    </div>   
</div>
@endsection
