<div>
    <select id="select-dropdown-{{$colName}}" class="w-full">
    @foreach($options as $option)
        <option value="{{$option}}">{{Str::headline( $option)}}</option>
    @endforeach
    </select>
</div>

<script type="text/javascript">$('#select-dropdown-{{$colName}}').select2({placeholder: "Please select", allowClear: false});</script>
