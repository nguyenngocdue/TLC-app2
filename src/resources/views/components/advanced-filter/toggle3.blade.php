<div class="mt-1">
<select id={{$name}} name={{$name}} component="advancefilter/dropdown" class='{{$classList}} py-2.5'>
    @foreach($dataSource as $key => $value)
        <option value={{$value}} @selected($value == $selected ? true : false)>
            {{$key}}
        </option>
    @endforeach
</select>
</div>
