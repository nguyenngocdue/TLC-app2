@php
    $svg = "<span class='text-green-700 font-bold text-3xl'>✓</span>";
	$checked = !!$slot->__toString();
@endphp

{{-- {{$checked}} --}}
{!! $checked ? $svg : "" !!}