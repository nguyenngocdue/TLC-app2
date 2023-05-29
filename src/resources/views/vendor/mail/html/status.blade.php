@php
    $status = $slot->__toString();
    $statuses = App\Http\Controllers\Workflow\LibStatuses::getAll();
    $color = isset($statuses[$status]) ? $statuses[$status]['color'] : 'red';
    $colorIndex = isset($statuses[$status]) ? $statuses[$status]['color_index'] : 200;
    $bgIndex = 1000 - $colorIndex;
    $nameIndex = $color . '-'.$colorIndex;
    $nameIndexBg = $color . '-'.$bgIndex;
    $color = App\Utils\ConvertColorTailwind::$colors[$nameIndex] ?? 'Unknown';
    $colorBg = App\Utils\ConvertColorTailwind::$colors[$nameIndexBg] ?? 'Unknown';
    $title = isset($statuses[$status]) ? $statuses[$status]['title'] : ($status ? Str::headline($status) : "null");
@endphp
<span style="background-color: {{$color}}; color: {{$colorBg}};  border-radius: 0.25rem; padding:0.25rem 0.5rem; font-weight:500; white-space: nowrap;">{{$title}}</span>
