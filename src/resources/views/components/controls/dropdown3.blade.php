<select id="{{$name}}" class=" bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white select2-hidden-accessible" multiple="multiple" style="width: 100%;" name="{{$name}}[]" tabindex="-1" aria-hidden="true">
    @foreach($dataSource as $value)
    <option value="{{$value->id}}" @selected($valueSelected ? in_array($value->id,$valueSelected) : null) >{{$value->name}}</option>
    @endforeach
  </select>
  <script>
        $('[id="'+"{{$name}}"+'"]').select2({
            placeholder: "Please Select"
            , allowClear: true
            , templateResult: select2FormatState
        });
</script>