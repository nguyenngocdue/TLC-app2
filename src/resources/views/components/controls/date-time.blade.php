<input 
    name="{{$name}}"
    id="{{$name}}" 
    class='bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input {{$readOnly ? 'readonly' : ''}}' 
    value='{{old($name, ($value))}}' 
    placeholder="{{$placeholder}}"
    {{$readOnly ? 'readonly' : ''}}
    />
