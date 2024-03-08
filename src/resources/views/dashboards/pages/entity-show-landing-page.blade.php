@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code" )

@section('content')
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1300px] bor9der bg-white box-border flex justify-center">
        <div class="w-full">
            <div class="mx-auto w1-96 h-96 bg-white dark:bg-gray-800 dark:border-gray-700 rounded-lg transform transition duration-500 hover1:scale-110">
                <div class="h-80">
                    <img class="w-full h-full object-cover" src="{{$thumbnailUrl}}" alt="image" 
                    >
                </div>
                    <span class="mt-2 text-base font-semibold flex justify-center truncate">{{$moduleName}}</span>
            </div>
            <div class="w-auto justify-center mt-6 mx-auto px-4">
                <div class="lg:grid lg:grid-cols-2 gap-5">
                    @foreach($dataSource as $index => $group)
                    <x-renderer.card title="{{$index}}">
                        @foreach($group as $value)
                            <x-print.hyper-link5 label="{{$value['name']}}" href="{{$value['href'] ?? ''}}" />
                        @endforeach
                    </x-renderer.card>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
