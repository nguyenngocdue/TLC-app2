@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4 bg-body">
    <x-navigation.pill/>
    <form action="{{$route}}" method="POST">
        @csrf
        <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo=true maxH=32></x-renderer.table>
        <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
    </form>
    <br />
    <hr />
    {{-- <x-form.create-new action="{{$route}}/create"/> --}}
</div>
@endsection