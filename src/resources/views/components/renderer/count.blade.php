@php
    $json = json_decode($slot);
    if(!is_array($json)) $json=[$json];
    $count = sizeof($json);
    $str = Str::of('item')->plural($count);
    $str = $count. " " . $str;
@endphp
<div class="text-center"><x-renderer.tag>{{$str}}</x-renderer.tag></div>