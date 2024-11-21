@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4 bg-body">
    <x-navigation.pill />
    <form action="{{$route}}" method="POST">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <!-- @dump($columns); -->
        <x-renderer.table 
            showNo=1 
            :columns="$columns" 
            :dataSource="$dataSource" 
            {{-- tableTrueWidth=1  --}}
            maxH={{512}}
            
            rotate45Width=200
            rotate45Height=150
            />
        <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
        <!-- Update button need name as 'button' to avoid the submit of the "UP" button on the same form -->
    </form>
    <br />
    <hr />
    <x-form.create-new action="{{$route}}/create"/>
</div>
@endsection