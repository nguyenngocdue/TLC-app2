@php
$class = $class ?? "";
$svg = $svg ?? "";
$title = $title ?? "";
@endphp
<div class="flex text-sm p-4 border rounded-lg {{$class}}" role="alert">
    {!!$svg!!}
    <span class="sr-only">{{$title}}</span>
    <div>
        <span class="font-medium">{{$title}}: {!!$message!!}</span>
    </div>
</div>
