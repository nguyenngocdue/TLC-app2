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

        //Using nginx redirect
        // $route = route($routeName, $item->slug);
        $route = "https://app.tlcmodular.com/modular/".$item->slug;
        $length = $item->length;
        $width = $item->width;
        $height = $item->height;
        $weight = $item->weight;
        $manufactured_year = $item->manufactured_year;
    @endphp
        <div component="card" class="col-span-12 lg:col-span-6 flex justify-center">
            <div class="  mt-10">
                <div class="mt-1 border border-gray-800 rounded-3xl mx-5 text-center" style="width: 600px;">&lt;--- scale to 10 cm ---&gt;</div>
                <div component="fixedWidth" class="border border-gray-800 rounded-3xl bg-white p-10 mx-5 my-1" style="width: 600px;">
                    <div class="flex justify-between mb-4">
                        <span class=""><img width="200px" src="{{$com_logo}}" /></span>
                        <span class="text-xl font-bold text-center px-10">{{$com_name}}</span>
                    </div>
                    {{-- {{$item['name']}}
                    {{$item['href']}} --}}
                    <div class="flex">
                        <div class="grid grid-cols-12 mr-4 w-full text-sm">
                            <div class="col-span-5 text-right mr-4 font-semibold">CLIENT</div><div class="col-span-7">{!! $clientName !!}</div>
                            <div class="col-span-5 text-right mr-4 font-semibold">PROJECT</div><div class="col-span-7">{!! $projectName !!}</div>
                            <div class="col-span-5 text-right mr-4 font-semibold">MFG YEAR</div><div class="col-span-7">{!!$manufactured_year?? $red!!}</div>
                            <div class="col-span-5 text-right mr-4 font-semibold">SERIAL NUMBER</div><div class="col-span-7">{{$item["name"]}}</div>
                            <div class="col-span-5 text-right mr-4 font-semibold">SIZE</div><div class="col-span-7">{!! $length ?? $red !!}M x {!!$width?? $red!!}M x {!!$height?? $red!!}M</div>
                            <div class="col-span-5 text-right mr-4 font-semibold">WEIGTH</div><div class="col-span-7">{!!$weight?? $red!!} TONS</div>
                        </div>
                        <span id="{{$route}}" class=""></span>
                    </div>
                    <div class="text-center mt-4">{{$com_website}}</div>
                </div>
                <script>
                new QRCode(document.getElementById("{{$route}}"),"{{$route}}",)
                </script>
            </div>
        </div>
        {!! (($index + 1) % 4 ===0 && $index < sizeof($dataSource)-1 ) ? "<div class='pagebreak col-span-12'>----Please Print in lanscape mode Scale 80% with no margin to have 4 items in a page------</div>" : "" !!}
@endforeach
</div>

@endsection
