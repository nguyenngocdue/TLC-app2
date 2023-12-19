@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<x-feedback.result type="success" title="Thank you" message="Thank you for <a href='{{$route}}' class='text-blue-500'>your respond</a>." />

@endphp
@endsection 