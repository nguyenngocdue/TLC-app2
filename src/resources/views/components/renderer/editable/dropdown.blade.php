<select 
    id="{{$name}}" 
    name="{{$name}}" 
    component="editable/dropdown"
    class="{{$classList}}">
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