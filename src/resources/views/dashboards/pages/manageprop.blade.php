@extends('layouts.app')

@section('title', 'Manage Props')

@section('content')
<form action="{{$route}}" method="POST">
    @csrf
    <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
    <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" />
    <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
    <!-- Update button need name as 'button' to avoid the submit of the "UP" button on the same form -->
</form>

<br />
<hr />
<x-form.create-new action="{{$route}}/create"/>
<br />
<br />
<br />
@endsection