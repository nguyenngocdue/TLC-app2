<select name="{{$name}}" class="block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 text-left placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm">
    @foreach($cbbDataSource as $line)
    <option 
        title="{{$line['value']}}" 
        value="{{$line['value']}}" 
        @selected($selected==$line['value'] )
        >
        {{$line['title']}}
    </option>
    @endforeach
</select>