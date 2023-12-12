@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div class="grid grid-cols-12 gap-2 p-4 w-full min-h-screen">
    <div class="col-span-12 text-center">
        <div class="flex w-full justify-center">
        <div class="w-1/3">
            <x-renderer.heading level=3 xalign=center>Welcome to Questionare system.</x-renderer.heading>

            <x-renderer.heading class="mt-10" level=4 xalign=center>The New questionares:</x-renderer.heading>
            @foreach($availableExams as $exam)
            <form action="{{$routeStore}}" method="POST" class="hover:bg-lime-200 rounded p-2">
                @csrf
                {{$exam->name}}
                <input type="hidden" name="owner_id" value="{{$cuid}}" />
                <input type="hidden" name="exam_tmpl_id" value="{{$exam->id}}" />
                <x-renderer.button htmlType="submit" type="secondary">Start</x-renderer.button>
            </form>
            @endforeach

            <x-renderer.heading class="mt-10" level=4 xalign=center>The In Progress questionares:</x-renderer.heading>
            @foreach($myInProgressSheets as $exam)
            @php
                $routeEdit = route(Str::plural($type) . '.edit', $exam->id);
            @endphp
            <form action="{{$routeEdit}}" method="GET" class="hover:bg-lime-200 rounded p-2">
                {{$exam->name}}
                <x-renderer.button htmlType="submit" type="success">Continue</x-renderer.button>
            </form>
            @endforeach

            <x-renderer.heading class="mt-10" level=4 xalign=center>The Finished questionares:</x-renderer.heading>
            @foreach($myFinishedSheets as $exam)
            @php
                $routeEdit = route(Str::plural($type) . '.edit', $exam->id);
            @endphp
            <form action="{{$routeEdit}}" method="GET" class="hover:bg-lime-200 rounded p-2">
                {{$exam->name}}
                {{-- <x-renderer.button htmlType="submit" type="primary">Open</x-renderer.button> --}}
            </form>
            @endforeach

        </div>
        </div>
    </div>
</div>

@endsection 