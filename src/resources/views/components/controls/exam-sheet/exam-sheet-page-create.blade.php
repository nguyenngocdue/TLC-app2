@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div class="grid grid-cols-12 gap-2 p-4 w-full min-h-screen">
    <div class="col-span-12 text-center">
        <div class="flex w-full justify-center">
        <div class="w-1/3">
            <x-renderer.heading level=3 xalign=center>Welcome to Questionare system.</x-renderer.heading>

            @foreach($availableExams as $exam)
            <form action="{{$route}}" method="POST" class="hover:bg-lime-200 rounded p-2">
                @csrf
                {{$exam->name}}
                <input type="hidden" name="owner_id" value="{{$cuid}}" />
                <input type="hidden" name="exam_tmpl_id" value="{{$exam->id}}" />
                <x-renderer.button htmlType="submit" type="secondary">Start</x-renderer.button>
            </form>
            @endforeach
        </div>
        </div>
    </div>
</div>

@endsection 