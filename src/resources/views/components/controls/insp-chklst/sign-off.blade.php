<x-renderer.card class="my-1 border" px="0" py="0">
    <div class="bg-amber-300 rounded-t p-2">
        <p>Third Party Sign Off</p>
    </div>
    <x-renderer.card title="Nominated Approvers" class='mx-4 border'>
        <div class="grid grid-cols-12 gap-1">
            <div class="col-span-12 md:col-span-8">
                <x-controls.has-data-source.dropdown2 type={{$type}} name='getMonitors1()' :selected="$selected" multiple={{true}} />
            </div>
            <div class="col-span-12 md:col-span-4">
                <x-renderer.button icon="fa-duotone fa-paper-plane" class="w-full h-full bg-lime-200">Send Reminder</x-renderer.button>
            </div>
        </div>
    </x-renderer.card>
    <div class="flex justify-center">
        <div class="p-4">
            @foreach($signatures as $signature)
                {{-- @dump($signature) --}}
                <div class="text-right">
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