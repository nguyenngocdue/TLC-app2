{{-- @if($type==='hidden')
{{$cell??$slot}}
@endif
@php
$align = $type==='number' ? "text-right" : "";
@endphp
<input name="{{$name}}" value="{{$cell??$slot}}" type="{{$type}}" placeholder="{{$placeholder}}" class="{{$align}} block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 focus:outline-none sm:text-sm"> --}}


<textarea 
    id="{{$name}}"
    name="{{$name}}"
    rows="2" 
    class="bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" 
    placeholder="Type here..."
    >{{old($name, $cell??$slot)}}</textarea>
