@php
    $isChecked = old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0;
    $isChecked = $isChecked ? 1 : 0;
    // dump($isChecked);
@endphp

@if($readOnly)
<div class="flex justify-center">
    <input name="{{$name}}" value={{$isChecked}} type="hidden"/>
    <label for="{{$name}}" class=" inline-flex relative items-center">
        <input @checked($isChecked) type="checkbox" class="sr-only peer" disabled>
        <div class="{{$classList}}"></div>
    </label>
</div>


@else
<div class="flex justify-center">
    <label for="{{$name}}" class=" inline-flex relative items-center cursor-pointer">
        <input @checked($isChecked) name={{$name}} type="checkbox" value={{$isChecked}} id="{{$name}}" class="sr-only peer">
        <div class="{{$classList}}"></div>
    </label>
</div>

@endif
