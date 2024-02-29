@extends('layouts.app')
@section('topTitle', 'Retrieve Diginet Data')
@section('title', 'Employee Overtime Lines')

@section('content')
<div class='p-10'>
    @include('diginet.include-diginet-transfer-data-table')
</div>
@endsection

