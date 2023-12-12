@php
    // dump($selected);
@endphp

<div id="{{$id}}" class="">
    {{$id}} - {{$selected}}
    @if($pass ?? false)
    <i class="fa-solid fa-square-check text-green-600 text-3xl mr-2"></i>
    @else
    <i class="fa-solid fa-square-xmark text-red-600 text-3xl mr-2"></i>
    @endif
</div>