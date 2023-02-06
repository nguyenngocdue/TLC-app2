<div>
    <p class="text-gray-600 text-xs pl-2.5 text-left pt-0.5">
    {{-- {{dd($dataTime, $day, $control)}} --}}
    @if(empty($day) && isset($dataTime[$control]))
        00/00/0000
    @else
        @foreach($dataTime as $key => $value)
            @if($key === $control && empty($day)!= true) 
            {{$value}}
            @break
            @endif
        @endforeach
    @endif
</p>
</div>
