<input name="{{$colName}}" class='bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' type='text' value='{{old($colName, $valColName)}}' placeholder="{{in_array($control, $timeControls) ? "YYYY-MM-DD" : ""}}">
@error($colName)
<span class="text-xs text-red-400 font-light" role="alert">
    <strong>{{$message}}</strong>
</span>
@enderror
