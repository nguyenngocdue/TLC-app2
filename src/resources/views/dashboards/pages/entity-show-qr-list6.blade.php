@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code List" )

@section('content')
{{-- @dd($dataSource) --}}
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1100px] items-center bor9der bg-white box-border">
        @foreach($dataSource as $keyGroup => $group)
            <div class="grid grid-cols-2 gap-4 p-5">
                @foreach($group as $keyItem => $item)
                <div class="w-full h-full bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div id="{{$keyGroup.$keyItem}}" class="w-50 h-50 flex m-5 items-center justify-center"></div>
                    <script>
                        new QRCode(document.getElementById("{{$keyGroup.$keyItem}}"),"{{$item['href']}}",)
                    </script>
                    <span class="text-base font-semibold flex justify-center truncate">{{$item['name']}}</span>
                    <span class="text-sm font-normal flex justify-center px-4 py-2">{{$item['href']}}</span>
                </div>
                @endforeach
            </div>
            @if(sizeof($dataSource) !== $keyGroup + 1)
                <div class="pagebreak"></div>
            @endif
        @endforeach
    </div>
    
</div>

@endsection
