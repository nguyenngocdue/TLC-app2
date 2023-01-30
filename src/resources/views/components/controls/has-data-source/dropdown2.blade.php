<select id="{{$id}}" name="{{$name}}" {{$multipleStr}} class="bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
    @foreach($dataSource as $item)
    <option value="{{$item['value']}}" @selected(in_array($item['value'], json_decode($selected))) title="{{$item['description']}}">{{$item['label']}}</option>
    @endforeach
</select>

@once
<script>
    function formatState(state) {
        if (!state.id) return state.text;
        return $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)
    };

</script>
@endonce

<script>
    $(document).ready(function() {
        $('#{{$id}}').select2({
            placeholder: "Please select"
            , allowClear: true
            , templateResult: formatState
        });
    })

</script>
