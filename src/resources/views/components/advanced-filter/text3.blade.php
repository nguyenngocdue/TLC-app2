<div class="relative w-full">
    @if($icon)
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
      <i class="{{$icon}}"></i>
    </div>
    @endif
    <input 
        component="controls/text2"
        id="{{$name}}" 
        name="{{$name}}" 
        placeholder="{{$placeholder}}" 
        value="{{old($name, ($value))}}"
        onchange='onChangeDropdown2("{{$name}}")'
        onkeypress="{{$onKeyPress}}"
        class='{{$classList}} {{$icon ? 'pl-10' : ''}} {{$readOnly ? 'readonly' : ''}}'
        {{$readOnly ? 'readonly' : ''}} 
        />
</div>