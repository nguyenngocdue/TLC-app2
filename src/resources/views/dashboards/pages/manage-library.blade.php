@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4">
    <form action="{{$route}}" method="post">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <x-renderer.table showNo=true groupBy="name" :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
        <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
    </form>
    <br />
    <hr />
    <x-form.create-new action="{{$route}}/create" />
</div>
@endsection