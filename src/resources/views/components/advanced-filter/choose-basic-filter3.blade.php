<div title="Choose Basic Filter">
    <select id="{{$name}}" class="select2-hidden-accessible" {{$multipleStr}} style="width: 100%;" name="{{$name}}" tabindex="-1" aria-hidden="true" onchange="updateBasicFilter()">
        @foreach($dataSource as $value)
        <option value="{{$value}}" @selected($valueControl ? $valueControl==$value : null) >{{$value ?? ''}}</option>
        @endforeach
    </select>
</div>

<script>
        $('[id="'+"{{$name}}"+'"]').select2({
            placeholder: "Please select..."
            , allowClear: false
            // , templateResult: select2FormatOption
        });
</script>