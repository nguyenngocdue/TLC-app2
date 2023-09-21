<td class='w-[{{$widthCell}}px] h-[60px]  {{$class1}} text-right border-t relative '>
    <div class="align-middle">
        <div class=' items-baseline flex justify-end'>
            <span class="{{$fontBold ?? ''}} items-center flex text-base">{{$tco2e > 0 ? number_format($tco2e, 2): ''}}
                @if($difference < 0)
                    <i class=" text-red-600 text-xs fa-solid fa-triangle rotate-180 pr-1"></i>
                @elseif($difference)
                    <i class="text-green-600 text-xs fa-solid fa-triangle pl-1"></i>
                @endif
            </span>
        </div>
        <div class=" flex justify-end">
            @if($difference < 0)
                <span class='text-red-600  {{$fontBold ?? ''}}'>{{$difference}}%</span>
            @elseif($difference)
                <span class='text-green-600  {{$fontBold ?? ''}}'>{{$difference}}%</span>
            @else
                <br/>
            @endif
        </div>
    </div>
</td>
