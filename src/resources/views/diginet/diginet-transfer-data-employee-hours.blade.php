@extends('layouts.app')
@section('topTitle', 'Retrieve Diginet Data')
@section('title', 'Employee Hours')

@section('content')
    @include('diginet.include-diginet-transfer-data-table')
@endsection

