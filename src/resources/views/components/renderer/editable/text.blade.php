@if($type==='hidden')
{{$slot}}
@endif
<input name="{{$name}}" value="{{$slot}}" type="{{$type}}" placeholder="{{$placeholder}}" class="block w-full rounded-md border bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 focus:outline-none sm:text-sm">