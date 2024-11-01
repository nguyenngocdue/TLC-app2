{{-- @dump($checkpoint) --}}
{{-- @dump($checkpoint->getControlGroup->getControlValues) --}}

{{-- @foreach($checkpoint->getControlGroup->getControlValues as $controlValue)
    @dump($controlValue->behaviour_of);
@endforeach --}}

<div class="flex items-center">
    <div class="w-1/2 flex text-xs-vw">
        @foreach($checkpoint->getControlGroup->getControlValues as $controlValue)
            {{-- Skip ON HOLD --}}
            @if($controlValue->behavior_of == config('insp_chklst.on_hold')) @continue @endif
            @if($checkpoint->getControlValue?->id == $controlValue->id)
                {{-- //CHECKED --}}
                <div class="border px-2 py-1 font-bold border-gray-700">
                    @php
                        $icon = $controlValue->icon_str ?: 'fas fa-check-circle';
                        $color = ($color = $controlValue->getColor->name) ? "text-$color-500" : 'text-green-500';
                    @endphp                     
                    <i class="{{$icon}} {{$color}}"></i> {{$controlValue->name}}                    
                </div>        
            @else
                {{-- //UNCHECKED --}}
                <div class="border px-2 py-1 bg-gray-300 border-gray-400">                   
                    <i class="far fa-circle text-gray-500"></i> {{$controlValue->name}}
                </div>
            @endif    
        @endforeach
    </div>

    <div class="w-1/2 flex items-center">
        <x-print.insp-chklst.check-point2-inspector-print :checkpoint="$checkpoint" />
    </div>
</div>

@if(isset($checkpoint->getNcrs) && !$checkpoint->getNcrs->isEmpty())
    <div class="font-bold">
        NCR(s):
    </div>
    @foreach($checkpoint->getNcrs as $ncr)
    <li>
        <a class="text-blue-500 cursor-pointer" href="{{route('qaqc_ncrs.show', $ncr->id)}}" target="_blank">            
            {{$ncr->name}} - Creator: {{$ncr->getOwner?->name}}
        </a>
    </li>
    @endforeach
@endif

@if(isset($checkpoint->getCorrectiveActions) && !$checkpoint->getCorrectiveActions->isEmpty())
    <div class="font-bold">
        Corrective Action(s):
    </div>
    @foreach($checkpoint->getCorrectiveActions as $ncr)
    <li>
        <a class="text-blue-500 cursor-pointer" href="{{route('hse_corrective_actions.show', $ncr->id)}}" target="_blank">            
            {{$ncr->name}} - Creator: {{$ncr->getOwner?->name}}
        </a>
    </li>
    @endforeach
@endif

