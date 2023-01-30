{{$selected}}
<select id="{{$name}}" name="{{$name}}">
    @foreach($dataSource as $item)
        <option 
            value={{$item['value']}} 
            @selected(in_array($item['value'], json_decode($selected)))
            >
            {{$item['label']}}
        </option>
    @endforeach
</select>

<script>
$('#{{$name}}').select2({
    placeholder: "Please select"
    , allowClear: true
    , templateResult: formatState
});
</script>