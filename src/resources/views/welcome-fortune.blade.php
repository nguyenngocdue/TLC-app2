@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php $groupName = ''; @endphp

<div class="grid grid-cols-12 gap-2 p-4 w-full">
    <div class="col-span-2 border rounded p-2">
        <x-navigation.table-of-contents :dataSource="$tableOfContents"/>
    </div>
    <div class="col-span-10 border rounded p-2">
        <div class="" >
            @if($isOnePage)
                <form>
                    @foreach($dataSource as $item)
                        @php
                            if($groupName != $item->getExamTmplGroup->name){
                                $groupName = $item->getExamTmplGroup->name;
                                @endphp
                                <x-renderer.heading level=3 >{{$groupName}}</x-renderer.heading>
                                {{$item->getExamTmplGroup->description}}
                                @php
                            }
                        @endphp
                        <x-question-answer.question-answer :item="$item"/>
                    @endforeach
                    <x-renderer.button htmlType="submit">Submit</x-render.button>
                </form>
            @endif
        </div>
    </div>
</div>

@endsection 