<select name="{{$name}}" class="block w-full rounded-md border bg-white px-1 py-2 text-left placeholder-slate-400 shadow-sm focus:border-purple-400 focus:outline-none sm:text-sm">
    @foreach($cbbDataSource as $line)
    <option title="{{$line['value']}}" value="{{$line['value']}}" @selected($slot==$line['value'] )>
        {{$line['title']}}
    </option>
    @endforeach
</select>