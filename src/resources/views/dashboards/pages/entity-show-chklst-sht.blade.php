@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Print" )

@section('content')

    <div class="block py-4 bg-body no-print" role="divider"></div>

    <x-print.insp-chklst.insp-chklst-page :sheet="$sheet" modelPath="$modelPath"/>
    
@endsection