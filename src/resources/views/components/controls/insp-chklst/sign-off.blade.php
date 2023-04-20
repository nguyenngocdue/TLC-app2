<x-renderer.card class="my-1 border" px="0" py="0">
    <div class="bg-amber-300 rounded-t p-2">
        <p>Third Party Sign Off</p>
    </div>
    <x-renderer.card title="Nominated Approvers" class='mx-4 border'>
        <div class="grid grid-cols-12 gap-1">
            <div class="col-span-12 md:col-span-9">
                <x-controls.has-data-source.dropdown2 type={{$type}} name='getMonitors1()' :selected="$selected" multiple={{true}} />
            </div>
            <div class="col-span-12 md:col-span-3">
                <x-renderer.button icon="fa-duotone fa-paper-plane" class="w-full h-full bg-lime-200">Remind</x-renderer.button>
            </div>
        </div>
        
    </x-renderer.card>
    <div class="flex justify-center">
        <div class="p-4">
            @php $index = 0; @endphp
            @foreach($signatures as $signature)
                {{-- @dump($signature) --}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][id]" value="{{$signature['id']}}">
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][owner_id]" value="{{$signature['owner_id']}}">
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][qaqc_insp_chklst_sht_id]" value="{{$signableId}}">
                <div class="text-right">
                    <div class="w-96 h-36">
                        <x-controls.signature2 
                            name="signatures[{{$index}}][value]"
                            value="{{$signature['value']}}"
                            updatable="{{$signature['updatable']}}"
                        />
                    </div>
                    <div>
                        <x-controls.insp-chklst.name-position :user="$signature['user']" />                        
                    </div>
                </div>
            @php $index ++; @endphp
            @endforeach
            @if(!$alreadySigned)
                <div class="text-right bg-lime-50">
                    <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][id]">
                    <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][owner_id]" value="{{$currentUser['id']}}">
                    <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][qaqc_insp_chklst_sht_id]" value="{{$signableId}}">
                    <div class="w-96 h-36">
                        <x-controls.signature2 
                            name="signatures[{{$index}}][value]"
                            value=""
                        />
                    </div>
                    <div>
                        <x-controls.insp-chklst.name-position :user="$currentUser" />                        
                </div>
            @else
                <x-feedback.alert type="success" titleless=1 message="You have signed off this document." />
            @endif
        </div>
    </div>
</x-renderer.card>