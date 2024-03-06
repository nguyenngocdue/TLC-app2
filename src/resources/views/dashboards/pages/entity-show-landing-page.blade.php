@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code" )

@section('content')
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1300px] bor9der bg-white box-border flex justify-center">
        <div class="w-full">
            <div class="mt-10 mx-auto w-96 h-96 p-2 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 rounded-lg transform transition duration-500 hover:scale-110">
                <div class="p-3 h-80">
                    <img class="w-full h-full object-cover" src="https://wp.tlcmodular.com/wp-content/uploads/2020/12/GHT.jpg" alt="image" 
                    >
                </div>
                    <span class="mt-2 text-base font-semibold flex justify-center truncate">{{$moduleName}}</span>
            </div>
            <div class="w-auto justify-center mt-6 mx-auto px-4">
                <div class="md:grid md:grid-cols-2 gap-5">
                    @foreach($dataSource as $index => $group)
                    <x-renderer.card title="{{$index}}">
                        @foreach($group as $value)
                            <x-print.hyper-link5 label="{{$value['name']}}" href="{{$value['href']}}" />
                        @endforeach
                    </x-renderer.card>
                    @endforeach
                </div>
                
            </div>
        </div>
        
    </div>
</div>
@endsection
