<input name="{{$colName}}" class='bg-white border border-gray-300 text-gray-900  rounded-lg  p-2.5   dark:placeholder-gray-400  block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input' type='text' value='{{old($colName, $valColName)}}' placeholder="{{in_array($control, $timeControls) ? "YYYY-MM-DD" : "Type here..."}}">
@error($colName)
<span class="text-xs text-red-400 font-light" role="alert">
    <strong>{{$message}}</strong>
</span>
@enderror
