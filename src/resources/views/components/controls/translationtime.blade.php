<div>
    @if(empty($day) && isset($dataTime[$control]))
    <p class="text-gray-600 text-xs">00/00/0000</p>
    @else
    @foreach($dataTime as $key => $value)
    @if($key === $control && empty($day)!= true)
    <p class="text-gray-600 text-xs">{{$value}}</p>
    @break
    @endif
    @endforeach
    @endif
</div>
