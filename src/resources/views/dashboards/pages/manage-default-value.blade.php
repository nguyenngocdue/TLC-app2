@extends('layouts.app')

@section('topTitle', Str::appTitle($type))
@section('title', $title)

@section('content')
<x-navigation.pill />
<form action="{{$route}}" method="POST">
    @csrf
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" maxH=32/>
    <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
</form>
<br />
<hr />
{{-- <x-form.create-new action="{{$route}}/create"/> --}}
<br />
<br />
<br />
@endsection