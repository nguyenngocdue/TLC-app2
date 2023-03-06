@php
    $className = "w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600";
@endphp
@if($readOnly)
<div class="flex justify-center">
    <label for="{{$name}}" class=" inline-flex relative items-center">
        <input @checked(old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0) name={{$name}} type="checkbox" value="1" id="{{$name}}" class="sr-only peer" disabled>
        <input type="hidden" @checked(old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0) name={{$name}} value="1" id="{{$name}}" class="sr-only peer">
        <div class="{{$className}}"></div>
    </label>
</div>

@else
<div class="flex justify-center">
    <label for="{{$name}}" class=" inline-flex relative items-center cursor-pointer">
        <input @checked(old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0) name={{$name}} type="checkbox" value="1" id="{{$name}}" class="sr-only peer">
        <div class="{{$className}}"></div>
    </label>
</div>

@endif
