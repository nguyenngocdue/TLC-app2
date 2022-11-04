@props(['color', 'value'])
@php
$color = $color ?? 'green';
$value = $value ?? 'value';
@endphp
<span class="rounded-full text-xs m-1 bg-{{$color}}-100 px-2 py-1 leading-tight text-{{$color}}-700 dark:bg-{{$color}}-700 dark:text-{{$color}}-100">{{ $value }}</span>