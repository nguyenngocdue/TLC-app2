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
                @foreach($tableOfContents as $group)
                {{-- @dump($group) --}}
                    <x-renderer.heading level=4 >{{$group->name}}</x-renderer.heading>
                    <div class="flex">
                        <div class="px-1"></div>
                        <div class="bg-blue-500 px-1 rounded"></div>
                        <div class="px-1"></div>                        
                        <div>
                            <div id="group_{{$group->id}}">{{$group->description}}</div>
                            @foreach($dataSource as $item)
                                @php if($item->exam_tmpl_group_id != $group->id) continue; @endphp
                                <x-question-answer.question-answer :item="$item"/>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="p-2">
                    <x-renderer.button type='primary' htmlType="submit">Submit</x-render.button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@endsection 