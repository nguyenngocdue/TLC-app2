@php
    $svg = "<span class='text-green-700 font-bold text-3xl'><i class='fa-duotone fa-square-check'></i></span>";
	$checked = !!$slot->__toString();
@endphp

{{-- {{$checked}} --}}
{!! $checked ? $svg : "" !!}