@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4 bg-body">
    <x-navigation.pill />
    <form action="{{$route}}" method="POST">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <x-renderer.table showNo={{true}} :columns="$columns" :dataSource="$dataSource" maxH={{32 * 16}} groupBy='relationship' groupByLength=100/>
        <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
    </form>
</div>
@endsection