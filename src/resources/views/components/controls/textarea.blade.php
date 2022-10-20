<textarea name="{{$colName}}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-white-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message...">{{old($colName, stripslashes($valColName))}}</textarea>
@error($colName)
<span class="text-xs text-red-400 font-light" role="alert">
    <strong id="{{$colName}}">{{$message}}</strong>
</span>
@enderror
