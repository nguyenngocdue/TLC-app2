@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code" )

@section('content')
<div class="flex justify-center bg-body">
    <div class="w-[1000px] mb-5 pb-10 bor9der bg-white box-border flex justify-center">
        <div class="w-full">
            <div class="mx-auto w1-96 h-96 bg-white dark:bg-gray-800 dark:border-gray-700 rounded-lg transform transition duration-500 hover1:scale-110">
                <div class="h-80">
                    <img class="w-full h-full object-cover" src="{{$thumbnailUrl}}" alt="image" >
                </div>
                <div class="p-4 text-base mx-auto bg-gray-200 truncate flex justify-center">
                    <div class="w-1/2 grid grid-cols-12">
                        @php
                            $unitName = $item->getPjUnit?->name;
                        @endphp

                        @if($unitName)
                        <div class="col-span-6 text-right">
                            Apartment / House Number:
                        </div>
                        <div class="col-span-6">
                            <span class="ml-1 font-semibold">{{$unitName}}</span>
                        </div>
                        @else
                        <div class="col-span-6 text-right">
                            Module Number: 
                        </div>
                        <div class="col-span-6">
                            <span class="ml-1 font-semibold">{{$item->name}}</span>
                        </div>
                        @endif
                    </div>
                </div>
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
