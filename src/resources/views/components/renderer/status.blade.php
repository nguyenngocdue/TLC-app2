@props(['href' => null])
@php
    $status = $slot->__toString();
    $statuses = App\Http\Controllers\Workflow\LibStatuses::getAll();
    $color = isset($statuses[$status]) ? $statuses[$status]['color'] : 'violet';
    $title = isset($statuses[$status]) ? $statuses[$status]['title'] : ($status ? Str::headline($status) : "null");
@endphp
@if($href)  <a href='{{$href}}'> @endif
<x-renderer.tag color='{{$color}}' title='{{$status}}' class='hover:bg-blue-400'>{{ $title }}</x-renderer.tag>
@if($href)  </a> @endif