@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code List" )

@section('content')

{{-- @dump($dataSource) --}}

@php
    $qr_code_plate = config("company")["qr_code_plate"];
    ["company_logo" =>$com_logo, "company_name" => $com_name, "company_website" => $com_website] = $qr_code_plate;
    $red = "<span class='bg-red-500 rounded p-1'>???</span>";
@endphp

<div class="grid grid-cols-12">
@foreach($dataSource as $index=> $item)
    @php
        // dump($item);
        $clientName = $red;
        $projectName = $red;
        $subProject = $item->getSubProject;

        if($subProject){
            $client = $subProject->getClient;
            if($client){
                // dump($client);
                $clientName = $client->name;
            }
            $project = $subProject->getProject;
            if($project){
                // dump($project);
                $projectName = $project->description;
            } 
        }

        $route = route($routeName, $item->slug);
        $length = $item->length;
        $width = $item->width;
        $height = $item->height;
        $weight = $item->weight;
        $manufactured_year = $item->manufactured_year;
    @endphp
        <div component="card" class="border border-gray-800 rounded-3xl bg-white p-10 m-5 col-span-12 lg:col-span-6">
            <div class="flex justify-between mb-4">
                <span class=""><img width="200px" src="{{$com_logo}}" /></span>
                <span class="text-xl font-bold text-center px-10">{{$com_name}}</span>
            </div>
            {{-- {{$item['name']}}
            {{$item['href']}} --}}
            <div class="flex">
                <div class="grid grid-cols-12 mr-4 w-full">
                    <div class="col-span-6 text-right mr-4">CLIENT</div><div class="col-span-6">{!! $clientName !!}</div>
                    <div class="col-span-6 text-right mr-4">PROJECT</div><div class="col-span-6">{!! $projectName !!}</div>
                    <div class="col-span-6 text-right mr-4">MANUFACTURED YEAR</div><div class="col-span-6">{!!$manufactured_year?? $red!!}</div>
                    <div class="col-span-6 text-right mr-4">SERIAL NUMBER</div><div class="col-span-6">{{$item["name"]}}</div>
                    <div class="col-span-6 text-right mr-4">SIZE</div><div class="col-span-6">{!! $length ?? $red !!}M x {!!$width?? $red!!}M x {!!$height?? $red!!}M</div>
                    <div class="col-span-6 text-right mr-4">WEIGTH</div><div class="col-span-6">{!!$weight?? $red!!} TONS</div>
                </div>
                <span id="{{$route}}" class=""></span>
            </div>
            <div class="text-center mt-4">{{$com_website}}</div>
        </div>
        <script>
            new QRCode(document.getElementById("{{$route}}"),"{{$route}}",)
            </script>
        {{-- {!! (($index + 1) % 4 ===0) ? "<div class='pagebreak'></div>" : "" !!} --}}
@endforeach
</div>

@endsection
