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
                    <div class="w-full text-center">
                        @php
                            $unitName = $item->getPjUnit?->name;
                        @endphp

                        @if($unitName)
                            <div>
                                Apartment No.:
                                <span class="ml-1 font-semibold">{{$unitName}}</span>
                            </div>
                            <div>
                                Module No.: 
                                <span class="ml-1 font-semibold">{{$item->name}}</span>
                            </div>
                        @else
                            <div>
                            {{-- No.:  --}}
                                <span class="ml-1 font-semibold">{{$item->name}}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="w-auto justify-center mt-6 mx-auto px-4">
                <div class="lg:grid lg:grid-cols-2 gap-5">
                    @foreach($dataSource as $index => $group)
                    <x-renderer.card title="{{$index}}" tooltip="{{$group['tooltip'] ?? ''}}">
                        @foreach($group['items'] as $name => $values)
                            @php
                                if(!is_numeric($name)) echo $name;
                            @endphp                            
                            @foreach($values as $value)
                            @php
                                    $name = $value['name'] ?? '';
                                    $href = $value['href'] ?? '';
                                @endphp
                                <x-print.hyper-link5 label="{!! $name !!}" href="{!! $href !!}" />
                            @endforeach
                          
                        @endforeach
                    </x-renderer.card>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
