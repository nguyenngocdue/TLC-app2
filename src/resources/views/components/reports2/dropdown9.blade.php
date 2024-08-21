<div class="w-full flex flex-col">
        <select onchange="redirectToLink(this)" name="{{ $name }}" id="{{ $name }}" class="w-full form-select bg-white border border-gray-300 text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="#" selected disabled>Select an other report</option>
            @foreach($rpLinks as $value)
                <option value="{{$value->id}}" 
                        data-url="{{route('rp_reports.show', $value->id)}}"
                        title="#{{ $value->id}}">
                    {{ $value->title ?? '(No title)' }}
                </option>
            @endforeach
        </select>
</div>

<script type="text/javascript">
    $('#{{$name}}').select2({
        placeholder: ''
        , allowClear: '{{$allowClear}}'
    });

</script>

<script>
function redirectToLink(select) {
    var selectedOption = select.options[select.selectedIndex];
    var url = selectedOption.getAttribute('data-url');
    select.options[0].style.display = 'none';
   if (url && selectedOption.value !== '#') {
        window.location.href = url;
    }
}
</script>

