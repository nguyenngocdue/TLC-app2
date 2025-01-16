@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code List" )

@section('content')

{{-- @dump($dataSource) --}}

@php
    $qr_code_plate = config("company")["qr_code_plate"];
    ["company_logo" =>$com_logo, "company_name" => $com_name, "company_website" => $com_website] = $qr_code_plate;
    $red = "<span class='bg-red-500 rounded px-1'>???</span>";
@endphp

<div class="grid grid-cols-12 min-h-screen">
@foreach($dataSource as $index=> $item)
    @php
        // dump($item);
        $clientName = $red;
        $projectName = $red;
        $subProject = $item->getSubProject;
        $style = null;

        if($subProject){
            $style = $subProject->qr_plate_style_id;
            $client = $subProject->getClient;
            if($client){
                $clientName = $client->name;
            }
            $project = $subProject->getProject;
            if($project){
                // dump($project);
                $projectName = $project->description;
            } 
        }

        // $route = route($routeName, $item->slug);
        //Using nginx redirect
        $route = "https://app.tlcmodular.com/modular/".$item->slug;
        $length = $item->length;
        $width = $item->width;
        $height = $item->height;
        $weight = $item->weight;
        $manufactured_year = $item->manufactured_year;
        $plot_number = $item->plot_number;
    @endphp
        <div component="card" class="col-span-12 lg:col-span-6 flex justify-center text-xs">
            @switch($style)
            @case(532)
            <x-qr-plate-style.style-2
                clientName="{!! $clientName !!}" red="{!! $red !!}" 
                projectName="{{$projectName}}" :item="$item" route="{{$route}}" 
                comLogo="{{$com_logo}}" comWebsite="{{$com_website}}" comName="{{$com_name}}"
                length="{{$length}}" width="{{$width}}" height="{{$height}}" weight="{{$weight}}" 
                manufacturedYear="{{$manufactured_year}}" plotNumber="{{$plot_number}}"
            />
            @break
            @case(797)
            <x-qr-plate-style.style-3 
                clientName="{!! $clientName !!}" red="{!! $red !!}" 
                projectName="{{$projectName}}" :item="$item" route="{{$route}}" 
                comLogo="{{$com_logo}}" comWebsite="{{$com_website}}" comName="{{$com_name}}"
                length="{{$length}}" width="{{$width}}" height="{{$height}}" weight="{{$weight}}" 
                manufacturedYear="{{$manufactured_year}}" plotNumber="{{$plot_number}}"
            />
            @break

            @default
            @case(531)
            <x-qr-plate-style.style-1 
                clientName="{!! $clientName !!}" red="{!! $red !!}" 
                projectName="{{$projectName}}" :item="$item" route="{{$route}}" 
                comLogo="{{$com_logo}}" comWebsite="{{$com_website}}" comName="{{$com_name}}"
                length="{{$length}}" width="{{$width}}" height="{{$height}}" weight="{{$weight}}" 
                manufacturedYear="{{$manufactured_year}}" plotNumber="{{$plot_number}}"
            />
            @break


            @endswitch
        </div>
        {!! (($index + 1) % 4 ===0 && $index < sizeof($dataSource)-1 ) ? "<div class='pagebreak col-span-12'>----Please Print in lanscape mode Scale 80% with no margin to have 4 items in a page------</div>" : "" !!}
@endforeach
</div>

@endsection
