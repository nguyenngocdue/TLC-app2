
@if($readOnly)
    <div class="flex-shrink-0 inline-flex justify-between  bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input {{$readOnly ? 'readonly' : ''}}">
        <input 
        type="hidden"
        id="{{$name}}" 
        name="status" 
        value="{{$value}}"
        class='{{$readOnly ? 'readonly' : ''}}'
        {{$readOnly ? 'readonly' : ''}} 
        />
        {{Str::headline( $value)}}
        <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </div>
@else
<div>
    <select name="status" id="select-dropdown-{{$name}}" class="w-full">
        @foreach($options as $option)
        <option value="{{$option}}" @selected($value===$option)>{{Str::headline( $option)}}</option>
        @endforeach
    </select>
</div>
<script type="text/javascript">
    $('#select-dropdown-{{$name}}').select2({
        placeholder: "Please select"
        , allowClear: false
    });

</script>
@endif

