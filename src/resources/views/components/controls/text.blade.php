<div class="relative w-full">
    @if($icon)
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
      <i class="{{$icon}}"></i>
    </div>
    @endif
    @php 
    $className = $icon ? 'pl-10' : '';
    @endphp
    <input 
        component="controls/text"
        id="{{$name}}" 
        name="{{$name}}" 
        placeholder="{{$placeholder}}" 
        value="{{old($name, ($value))}}"
        onchange='onChangeDropdown2("{{$name}}")'
        class='{{$className}} bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-600 focus:border-2 focus:outline-none dark:text-gray-300 dark:focus:shadow-outline-gray form-input {{$readOnly ? 'readonly' : ''}}'
        {{$readOnly ? 'readonly' : ''}} 
        />
</div>