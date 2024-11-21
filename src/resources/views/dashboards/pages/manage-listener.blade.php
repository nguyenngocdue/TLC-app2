@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4 bg-body">
    <x-navigation.pill />
    <form action="{{$route}}" method="POST">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo=true maxH={{32 * 16}} groupBy='listen_action' groupByLength={{100}}></x-renderer.table>
        <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
    </form>
    <br />
    <hr />
    {{-- <x-form.create-new action="{{$route}}/create"/> --}}
</div>
@endsection
