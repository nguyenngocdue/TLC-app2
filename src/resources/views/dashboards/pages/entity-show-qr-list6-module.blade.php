@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code List" )

@section('content')

{{-- @dump($dataSource) --}}

@php
    $qr_code_plate = config("company")["qr_code_plate"];
    ["company_logo" =>$com_logo, "company_name" => $com_name, "company_website" => $com_website] = $qr_code_plate;
@endphp

@foreach($dataSource as $item)
    <div component="card" class="border rounded w-1/2 bg-white p-10 m-5">
        <div class="flex justify-between mb-4">
            <span class=""><img width="200px" src="{{$com_logo}}" /></span>
            <span class="text-xl font-bold text-center px-10">{{$com_name}}</span>
        </div>
        {{-- {{$item['name']}}
        {{$item['href']}} --}}
        <div class="flex">
            <div class="grid grid-cols-12">
                <div class="col-span-6 text-right mr-4">CLIENT</div><div class="col-span-6">Client Name Is a very long text</div>
                <div class="col-span-6 text-right mr-4">PROJECT</div><div class="col-span-6">Project Name</div>
                <div class="col-span-6 text-right mr-4">MANUFACTURED YEAR</div><div class="col-span-6">2024</div>
                <div class="col-span-6 text-right mr-4">SERIAL NUMBER</div><div class="col-span-6">{{$item["name"]}}</div>
                <div class="col-span-6 text-right mr-4">SIZE</div><div class="col-span-6">100M x 60M x 20M</div>
                <div class="col-span-6 text-right mr-4">WEIGTH</div><div class="col-span-6">100 TONS</div>


            </div>
            <span id="{{$item['href']}}" class=""></span>
        </div>
        <div class="text-center mt-4">{{$com_website}}</div>
    </div>
    <script>
    new QRCode(document.getElementById("{{$item['href']}}"),"{{$item['href']}}",)
    </script>
@endforeach

@endsection
