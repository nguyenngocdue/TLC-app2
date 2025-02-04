@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<form id="virtual-table-form" action="/welcome-fortune" method="POST">
    @csrf
    @method('POST')


</form>


@endsection 

