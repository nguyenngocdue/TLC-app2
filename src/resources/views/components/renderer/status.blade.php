@php
    $status = $slot->__toString();
    $statuses = App\Http\Controllers\Workflow\LibStatuses::getAll();
    $color = isset($statuses[$status]) ? $statuses[$status]['color'] : 'violet';
    $title = isset($statuses[$status]) ? $statuses[$status]['title'] : ($status ? Str::headline($status) : "null");
@endphp
<x-renderer.tag color='{{$color}}' title='{{$status}}' class='mx-1'>{{$title}}</x-renderer.tag>