@if($readOnly)
<div class="flex justify-center">
    <label for="{{$name}}" class=" inline-flex relative items-center">
        <input @checked(old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0) name={{$name}} type="checkbox" value="1" id="{{$name}}" class="sr-only peer" disabled>
        <input type="hidden" @checked(old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0) name={{$name}} value="1" id="{{$name}}" class="sr-only peer">
        <div class="{{$classList}}"></div>
    </label>
</div>

@else
<div class="flex justify-center">
    <label for="{{$name}}" class=" inline-flex relative items-center cursor-pointer">
        <input @checked(old($name)*1==="1" || ! empty(old($name)) || $value !="" && $value*1 !==0) name={{$name}} type="checkbox" value="1" id="{{$name}}" class="sr-only peer">
        <div class="{{$classList}}"></div>
    </label>
</div>

@endif
