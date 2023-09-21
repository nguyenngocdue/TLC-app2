<td class='w-[{{$widthCell}}px] h-[60px]  {{$class1}} text-left border-t relative '>
    <div class="{{-- absolute --}}  align-middle">
        <div class=' items-baseline flex'>
            <span class="{{$fontBold ?? ''}} pr-2">{{$tco2e > 0 ? number_format($tco2e, 2): ''}}</span>
            @if($difference < 0)
                <i class=" text-red-600 text-[8px] fa-solid  fa-triangle rotate-180"></i>
            @elseif($difference)
                <i class="text-green-600 text-[8px] fa-sharp fa-solid fa-triangle"></i>
            @endif
        </div>
            @if($difference < 0)
                <span class='text-red-600  {{$fontBold ?? ''}}'>{{$difference}}%</span>
            @elseif($difference)
                <span class='text-green-600  {{$fontBold ?? ''}}'>{{$difference}}%</span>
            @else
                <br/>
            @endif
    </div>
</td>
