<div class="relative {{$class ? $class : 'w-full'}}">
    @if($icon)
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
      <i class="{{$icon}}"></i>
    </div>
    @endif
    @php
      $value = $value ? $value : old($name, ($value));
    @endphp
    <input
        component="{{$component}}"
        id="{{$name}}"
        name="{{$name}}"
        placeholder="{{$placeholder}}"
        value="{!!$value!!}"
        {{-- After a form is submitted, and a fail validation happen, this will only return the old, not the value --}}
        {{-- And if a week should be W10/2023, it will become 07/03/2023, and then it fails the next validation --}}
        {{-- value="{{old($name, ($value))}}" --}}
        {{-- onchange='onChangeDropdown2({name: "{{$name}}"})' --}}
        onchange="onChangeDropdown2({name: '{{$name}}' })"
        class='{{$classList}} {{$icon ? 'pl-10' : ''}} {{$readOnly ? 'readonly' : ''}} '
        {{$readOnly ? 'readonly' : ''}}
        onkeypress="{{$onkeypress}}"
        />
</div>
