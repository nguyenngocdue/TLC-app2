<div class="flex justify-center "> 
    <div class="{{$formWidth}}"> 
        <div class="border bg-white rounded-lg p-5">
            <x-renderer.heading level="4" class="text-center font-bold" title="#{{$item->id}}">
                {{strtoupper( $item->name)}}
            </x-renderer.heading>  
        </div>
    </div>
</div>