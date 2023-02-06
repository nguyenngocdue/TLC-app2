@php
$svgChecked = "<i class='fa-duotone fa-square-check'></i>";
$svgSquare = "<i class='fa-duotone fa-square'></i>";
$checked = !!$slot->__toString();
@endphp

{{-- {{$checked}} --}}
<span class='text-green-700 font-bold text-3xl'>
    {!! $checked ? $svgChecked : $svgSquare !!}
</span>
