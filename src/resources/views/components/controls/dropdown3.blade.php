<select id="{{$name}}" class="select2-hidden-accessible" multiple="multiple" style="width: 100%;" name="{{$name}}[]" tabindex="-1" aria-hidden="true">
    @foreach($dataSource as $value)
    <option value="{{$value->id}}" @selected($valueSelected ? in_array($value->id,$valueSelected) : null) disabled >{{$value->name ?? $value->id}}</option>
    @endforeach
  </select>
  <script>
        $('[id="'+"{{$name}}"+'"]').select2({
            placeholder: "Please select..."
            , allowClear: true
            , templateResult: select2FormatState
        });
</script>