@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div class="grid grid-cols-12 gap-2 p-4 w-full">
    <div class="col-span-2 border rounded p-2">
        <x-navigation.table-of-content :dataSource="$dataSource"/>
    </div>
    <div class="col-span-10 border rounded p-2">
        <div class="" >
            @if($isOnePage)
                @foreach($dataSource as $item)
                    <x-question-answer.question-answer :item="$item"/>
                @endforeach
            @endif
        </div>
    </div>
</div>

@endsection 