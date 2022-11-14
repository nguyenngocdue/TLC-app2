@php
$count = sizeof(json_decode($slot));
$str = Str::of('item')->plural($count);
$str = $count. " " . $str;
@endphp
<x-renderer.tag>{{$str}}</x-renderer.tag>