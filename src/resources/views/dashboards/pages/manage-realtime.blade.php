@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4">
    <x-navigation.pill />
    <form action="{{$route}}" method="POST">
        @csrf
        <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" maxH={{512}}/>
        <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
    </form>
    <br />
    <hr />
    {{-- <x-form.create-new action="{{$route}}/create"/> --}}
</div>
@endsection