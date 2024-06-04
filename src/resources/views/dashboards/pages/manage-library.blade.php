@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4 pt-4 bg-body">
    <x-elapse title="Bootraping: "/>
    <form action="{{$route}}" method="post">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <x-renderer.table 
            showNo=1
            groupBy="{{$groupBy}}" 
            groupByLength="{{$groupByLength}}" 
            :columns="$columns" 
            :dataSource="$dataSource"

            tableTrueWidth=1 
            rotate45Width="200"
            rotate45Height="150"
            ></x-renderer.table>

        <x-renderer.button type="primary" htmlType='submit'>Update</x-renderer.button>
    </form>
    <br />
    <hr />
    @if($allowedCreateNew)
        <x-form.create-new action="{{$route}}/create" />
    @else
        This screen is not allow to create new directly, please use its parent screen to create intead.
    @endif
</div>
@endsection