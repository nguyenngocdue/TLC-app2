<div class="flex justify-center "> 
    <div class="{{$formWidth}}"> 
        <x-renderer.heading level="4" xalign="center" title="#{{$item->id}}">
            {{strtoupper( $item->name)}}
        </x-renderer.heading>
    
        <div class=" border bg-white rounded p-5">
            <div class="flex justify-between">
                <div class="grid grid-cols-12">
                    <div class="col-span-6 mr-2 text-right">Project:</div><div class="col-span-6"> {{$item->getChklst->getSubProject->name}}</div>
                    <div class="col-span-6 mr-2 text-right">Module:</div><div class="col-span-6"> {{$item->getChklst->getProdOrder->production_name}}</div>
                </div>
                <div class="mx-4">
                    <img class="w-40" src="{{asset('logo/tlc.png')}}" />
                </div>
            </div>
        </div>      
    </div>
</div>