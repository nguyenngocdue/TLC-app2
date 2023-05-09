@props(['href' => null])
@php
    $status = $slot->__toString();
    $statuses = App\Http\Controllers\Workflow\LibStatuses::getAll();
    $color = isset($statuses[$status]) ? $statuses[$status]['color'] : 'violet';
    $colorIndex = isset($statuses[$status]) ? $statuses[$status]['color_index'] : 200;
    $bgIndex = 1000 - $colorIndex;
    $title = isset($statuses[$status]) ? $statuses[$status]['title'] : ($status ? Str::headline($status) : "null");
    $class = "hover:bg-{$color}-{$bgIndex} hover:text-{$color}-{$colorIndex}";
@endphp
@if($href)  <a href='{{$href}}'> @endif
<x-renderer.tag color='{{$color}}' colorIndex='{{$colorIndex}}' title='{{$status}}' class='{{$class}}'>{{ $title }}</x-renderer.tag>
@if($href)  </a> @endif