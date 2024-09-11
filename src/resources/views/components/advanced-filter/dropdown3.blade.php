
<div class="select2-short">
    <select id="{{$name}}" class="select2-hidden-accessible" multiple="multiple" style="width: 100%;" name="{{$name}}[]" tabindex="-1" aria-hidden="true">
        @foreach($dataSource as $value)
        <option value="{{$value->id ?? $value['id'] ?? ''}}" title="{{$value->employeeid ?? $value['employeeid'] ?? ''}}" @selected($valueSelected ? in_array($value->id ?? $value['id'],$valueSelected) : null) >{{$value->name ?? $value['name'] ?? ''}}</option>
        @endforeach
    </select>
      <script>
            $('[id="'+"{{$name}}"+'"]').select2({
                placeholder: "Please select..."
                , allowClear: true
                , templateResult: select2FormatOption
            });
    </script>
</div>
