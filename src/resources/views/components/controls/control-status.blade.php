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
