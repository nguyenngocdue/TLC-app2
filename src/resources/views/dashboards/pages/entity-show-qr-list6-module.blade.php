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
        $plot_number = $item->plot_number;
    @endphp
        <div component="card" class="col-span-12 lg:col-span-6 flex justify-center text-xs">
            <div class="  mt-10">
                <div class="mt-1 border border-gray-800 rounded-3xl mx-5 text-center" style="width: 600px;">&lt;--- scale to 8 cm ---&gt;</div>
                <div component="fixedWidth" class="border border-gray-800 rounded-3xl bg-white p-10 mx-5 my-1" style="width: 600px;">
                    
                    {{-- {{$item['name']}}
                    {{$item['href']}} --}}
                    <div class="-ml-5 mr-5">
                        <div class="flex" component="QR Code Group">
                            <div class="grid grid-cols-12 mr-4 w-full">
                                <div class="col-span-5 text-right mr-4 font-semibold">CLIENT</div><div class="col-span-7 uppercase">{!! $clientName !!}</div>
                                <div class="col-span-5 text-right mr-4 font-semibold">PROJECT</div><div class="col-span-7 uppercase">{!! $projectName !!}</div>
                                <div class="col-span-5 text-right mr-4 font-semibold">MFG YEAR</div><div class="col-span-7 uppercase">{!!$manufactured_year?? $red!!}</div>
                                <div class="col-span-5 text-right mr-4 font-semibold">PLOT NUMBER</div><div class="col-span-7 uppercase">{!!$plot_number?? $red!!}</div>
                                <div class="col-span-5 text-right mr-4 font-semibold">SERIAL NUMBER</div><div class="col-span-7 uppercase">{{$item["name"]}}</div>
                                <div class="col-span-5 text-right mr-4 font-semibold">SIZE</div><div class="col-span-7 uppercase">{!! $length ?? $red !!}M × {!!$width?? $red!!}M × {!!$height?? $red!!}M</div>
                                <div class="col-span-5 text-right mr-4 font-semibold">WEIGTH</div><div class="col-span-7 uppercase">{!!$weight?? $red!!} TONS</div>
                            </div>
                            <span id="{{$route}}" class=""></span>
                        </div>
                        <div class="my-4"></div>
                        <div class="flex justify-around">
                            <div class="w-48 h-32 ml-12 mr-4 flex items-center justify-center">
                                <div>
                                    <img width="200px" src="{{$com_logo}}" />
                                    <div class="text-center uppercase">{{$com_website}}</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 w-full">
                                <div class="col-span-12 text-center font-bold">MR ALISTAIR WILLIAM RAGLAN SAWER</div>
                                <div class="col-span-5 text-right font-semibold mr-4">EMAIL</div><div class="col-span-7">alistair@tlcmodular.com</div>
                                <div class="col-span-5 text-right font-semibold mr-4">HEAD OFFICE </div><div class="col-span-7">326 VO VAN KIET ST,</div>
                                <div class="col-span-5 text-right font-semibold mr-4"></div><div class="col-span-7">CO GIANG WARD, DISTRICT 1</div>
                                <div class="col-span-5 text-right font-semibold mr-4"></div><div class="col-span-7">HO CHI MINH CITY, VIETNAM</div>
                                <div class="col-span-5 text-right font-semibold mr-4">TELEPHONE</div><div class="col-span-7">+84 (0) 28 7301 8588</div>
                                <div class="col-span-5 text-right font-semibold mr-4"></div><div class="col-span-7">+84 (0) 28 7306 7779</div>
                                <div class="col-span-5 text-right font-semibold mr-4">FAX</div><div class="col-span-7">+84 (0) 28 3824 5317</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">{{$com_name}}</div>
                </div>
                <script>
                new QRCode(document.getElementById("{{$route}}"),{
                    text: "{{$route}}",
                    width: 140,
                    height: 140,
                },)
                </script>
            </div>
        </div>
        {!! (($index + 1) % 4 ===0 && $index < sizeof($dataSource)-1 ) ? "<div class='pagebreak col-span-12'>----Please Print in lanscape mode Scale 80% with no margin to have 4 items in a page------</div>" : "" !!}
@endforeach
</div>

@endsection
