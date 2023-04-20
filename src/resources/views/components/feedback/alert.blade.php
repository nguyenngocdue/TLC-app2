@php
$class = $class ?? "";
$svg = $svg ?? "";
$title = $title ?? "";
@endphp
<div class="flex text-sm p-4 border rounded-lg {{$class}}" role="alert">
    <i class="mr-1 w-4 h-4 {{$svg}}"></i>
    <div>
        <span class="font-medium">@if(!$titleless){{$title}}:  @endif {!!$message!!}</span>
    </div>
</div>
