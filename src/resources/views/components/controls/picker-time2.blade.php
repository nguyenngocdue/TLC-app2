@php if (!is_null(old($name))) $value = old($name); @endphp
<div class="relative w-full">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none "><i class="fa-duotone fa-calendar"></i></div>
    <input  name="{{$name}}" 
            id="{{$name}}"
            value="{{$value}}" 
            component="{{$component}}"
            {{$readOnly ? 'readonly' : ''}}
            placeholder="HH:MM" 
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 
                    {{$readOnly ? 'readonly' : ''}} {{$readOnly ? 'pointer-events-none' : ''}}"  
            onchange="onChangeDropdown2({name: '{{$name}}' })"
    />
</div>

<script>initFlatPickrTime("{{$name}}");</script>
