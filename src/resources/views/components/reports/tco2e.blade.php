<td class='w-[{{$widthCell}}px] h-[60px] text-right border-t border-l p-2 border-gray-600 relative '>
    <div class="align-middle">
        <div class=' items-baseline flex justify-end'>
        {{-- @dump($tco2e) --}}
        @if(!is_null($tco2e))
            <span class="{{$fontBold ?? ''}} items-center flex text-base">{{$tco2e > 0 ? (number_format($tco2e, 2)): ''}}
                @if($difference < 0)
                    <i class="  text-green-600 text-xs fa-solid fa-triangle rotate-180 pr-1"></i>
                @elseif($difference > 0)
                    <i class=" text-red-600 text-xs fa-solid fa-triangle pl-1"></i>
                @elseif((int)$difference === 0 && !is_null($difference))
                <i class="text-yellow-600 fa-solid fa-minus fa-xl" style="transform: scale(0.7, 1.5);margin-right: -5px;" ></i>
                @else
                @endif
            </span>
        </div>
        <div class=" flex justify-end">
            @if($difference < 0)
                <span class='text-green-600  {{$fontBold ?? ''}}'>{{abs($difference)}}%</span>
            @elseif($difference > 0)
                <span class='text-red-600  {{$fontBold ?? ''}}'>{{abs($difference)}}%</span>
            @elseif((int)$difference === 0 && !is_null($difference))
                <span class='text-yellow-600  {{$fontBold ?? ''}}'>0%</span>
                <br/>
            @else
                <span class='text-white'><br/></span>
            @endif
        </div>
        @else
            
        @endif
    </div>
</td>
