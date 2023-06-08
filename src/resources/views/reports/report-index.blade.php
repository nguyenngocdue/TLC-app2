@extends('layouts.app')

@section('topTitle', 'Report Index')
@section('title', '')

@section('content')

{{-- @dd($listOfReports) --}}
<div class="min-h-[1000px]">
    <ul>
        @foreach ($dataSource as $plural => $listOfReports)
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t"> {{ $titles[$plural] }}</li>
        <ul class="flex px-10">
            @foreach ($listOfReports as $name => $paths)
                @foreach($paths as $mode => $path)
                    <li class="text-blue-400 hover:bg-gray-200 p-2 rounded-md"><a href='{{route($path['path'])}}'>{{$path['title']}} ({{$name}} {{$mode}})</a></li>
                @endforeach
            @endforeach
        </ul>
        @endforeach
    </ul>
</div>
@endsection
