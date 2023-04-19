<x-renderer.card class="my-1 border" px="0" py="0">
    <div class="bg-amber-300 rounded-t p-2">
        <p>Third Party Sign Off</p>
    </div>
    <div class="flex justify-center">
        <div class="p-4">
            @foreach($signatures as $signature)
                {{-- @dump($signature) --}}
                <div class="">
                    <div class="w-96 h-36">
                        <x-controls.signature2 
                            name="AAA"
                            value="{{$signature['value']}}"
                        />
                    </div>
                    <div>
                        <x-controls.insp-chklst.name-position :user="$signature['user']" />                        
                    </div>
                </div>
        @endforeach
        </div>
    </div>
</x-renderer.card>