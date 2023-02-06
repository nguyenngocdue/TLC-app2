{{-- @if($type==='hidden')
{{$cell??$slot}}
@endif --}}
@php
$align = $type==='number' ? "text-right" : "";
@endphp
<input name="{{$name}}" value="{{$cell??$slot}}" type="{{$type}}" placeholder="{{$placeholder}}" 
    class="{{$align}} block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 focus:outline-none sm:text-sm">
