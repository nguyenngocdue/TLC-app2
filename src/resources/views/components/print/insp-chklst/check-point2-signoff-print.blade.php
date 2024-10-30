@if(isset($signOff) && !$signOff->isEmpty())
    <br/>
    <div class="uppercase font-bold w-full text-lg-vw text-gray-600 bg-gray-200 p-2 border border-gray-600">
        Third Party Sign-off
    </div>
    {{-- This fail when only 1 signature is present --}}
    {{-- @php $count = $signOff->count(); $count = $count > 3 ? 3 : $count; @endphp --}}
    <div class="border border-gray-500 p-2 w-full flex justify-center">
        @foreach($signOff as $signature)
            @php
                $stamp = null;
                if(in_array($signature->signature_decision,["approved", "rejected"])){
                    $stamp = asset('stamps/'.$signature->signature_decision.'.png');
                }
            @endphp
            <div class="text-center border border-gray-500 w-1/3">
                @if($stamp)
                    <img src="{{$stamp}}" class="w-1/2 mx-auto" />
                @else
                    <div class="uppercase font-bold px-4 py-2 mx-4 border rounded">
                        {{$signature->signature_decision}}
                    </div>
                @endif
                <div class="flex justify-center px-4 py-4">
                    <x-controls.signature.signature2a 
                        name='signOff_{{$signature->id}}' 
                        value='{{$signature->value}}' 
                        readOnly=1                             
                        />
                </div>
                <div class="flex justify-center px-4 pb-4">
                    <x-print.insp-chklst.check-point2-inspector-print :checkpoint="$signature" getUser=1 />
                </div>
            </div>
        @endforeach
    </div>
@endif