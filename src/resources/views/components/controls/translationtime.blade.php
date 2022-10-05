<div>
    @switch($control)
    @case('datetime')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @case('picker_date')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @case('picker_time')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @case('picker_year')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @case('picker_week')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @case('picker_month')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @case('picker_quater')
    <p class="text-gray-600 text-xs">{{$dataTime[$control]}}</p>
    @break
    @default
    @break

    @endswitch
</div>
