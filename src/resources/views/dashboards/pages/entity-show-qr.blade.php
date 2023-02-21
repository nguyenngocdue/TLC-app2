@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
{{-- @dd($dataSource) --}}
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1300px] items-center border bg-white box-border">
        <x-renderer.letter-head showId="list-qr-code" type={{$type}} />
        <div class="grid grid-cols-2 gap-4">
            @foreach($dataSource as $key => $value)
            <div id="{{$key}}" class="w-50 h-50 flex m-5 items-center justify-center"></div>
            <script>
                new QRCode(document.getElementById("{{$key}}"),"{{$value}}",)
            </script>
            @endforeach
        </div>
        
            {{-- @foreach($propsTree as $propTree)
                <x-renderer.description-group type={{$type}} modelPath={{$modelPath}} :propTree="$propTree" :dataSource="$dataSource" />
            @endforeach --}}

    </div>
    
</div>

@endsection
