<div>
    {{-- {{dd($dataTime, $day, $control)}} --}}
    @if(empty($day) && isset($dataTime[$control]))
    <p class="text-gray-600 text-xs pl-2.5 text-left pt-0.5">00/00/0000</p>
    @else
    @foreach($dataTime as $key => $value)
    @if($key === $control && empty($day)!= true)
    <p class="text-gray-600 text-xs pl-2.5 text-left pt-0.5">{{$value}}</p>
    @break
    @endif
    @endforeach
    @endif
</div>