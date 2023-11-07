@props(['href' => null, 'title' => null, 'tooltip' => null])
@php
    $status = $slot->__toString();
    $statuses = App\Http\Controllers\Workflow\LibStatuses::getAll();
    $color = isset($statuses[$status]['color']) ? $statuses[$status]['color'] : 'violet';
    $colorIndex = isset($statuses[$status]['color_index']) ? $statuses[$status]['color_index'] : 200;
    $bgIndex = 1000 - $colorIndex;
    if(is_null($title)){
        $title = isset($statuses[$status]) ? $statuses[$status]['title'] : ($status ? Str::headline($status) : "null");
    }
    if(is_null($tooltip)){
        $tooltip = $status;
    }
    $class = "hover:bg-{$color}-{$bgIndex} hover:text-{$color}-{$colorIndex}";
@endphp
@if($href)  <a href='{{$href}}'> @endif
<x-renderer.tag color='{{$color}}' colorIndex='{{$colorIndex}}' title='{{$tooltip}}' class='{{$class}}'>{{ $title }}</x-renderer.tag>
@if($href)  </a> @endif