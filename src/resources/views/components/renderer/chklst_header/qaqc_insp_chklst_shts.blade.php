@php
    $subProject = $item->getChklst->getSubProject;
    $project = $subProject->getProject;
    $prodOrder = $item->getChklst->getProdOrder; 
@endphp
<div class="flex justify-center "> 
    <div class="{{$formWidth}}"> 
        <x-renderer.heading level="4" xalign="center" title="#{{$item->id}}">
            {{strtoupper( $item->name)}}
        </x-renderer.heading>
    
        <div class=" border bg-white rounded p-5">
            <div class="flex justify-between">
                <div class="grid grid-cols-12">
                    <div class="col-span-6 mr-2 text-right">Project:</div><div class="col-span-6"> {{$project->description." / ".$subProject->name}}</div>
                    <div class="col-span-6 mr-2 text-right">Production Name:</div><div class="col-span-6"> {{$prodOrder->production_name}}</div>
                </div>
                <div class="mx-4">
                    <img class="w-40" src="{{asset('logo/tlc.png')}}" />
                </div>
            </div>
        </div>      
    </div>
</div>