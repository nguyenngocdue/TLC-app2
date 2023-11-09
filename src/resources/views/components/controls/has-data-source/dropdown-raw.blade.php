<select id='{{$id}}' name='{{$name}}'>
    @foreach($dataSource as $key=>$value)
        <option value='{{$key}}' @selected(in_array($key, $selected))>{{$value}}</option>
    @endforeach
</select>

<script>
    $("#{{$id}}").select2();
</script>