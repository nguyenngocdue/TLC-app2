<td class='w-{{$widthCell}} {{$class1}} text-left border-t'>
    <div class='p-2 flex items-baseline'>
        <span class="{{$fontBold ?? ''}} pr-2">{{$tco2e > 0 ? number_format($tco2e, 2): ''}}</span>
        @if($difference < 0)
            <span class='p-2 text-red-600  border-l-2 {{$fontBold ?? ''}}'><i class=" fa-solid fa-triangle rotate-180"></i>({{$difference}}%)</span>
        @elseif($difference)
            <span class='p-2 text-green-600  border-l-2 {{$fontBold ?? ''}}'><i class="fa-sharp fa-solid fa-triangle"></i></i>({{$difference}}%)</span>
        @endif
    </div>
</td>