@if($type==='hidden')
{{$slot}}
@endif
@php
$align = $type==='number' ? "text-right" : "";
@endphp
<input name="{{$name}}" value="{{$slot}}" type="{{$type}}" placeholder="{{$placeholder}}" class="{{$align}} block w-full rounded-md border bg-white px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 focus:outline-none sm:text-sm">