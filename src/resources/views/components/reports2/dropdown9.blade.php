<div class="w-full flex flex-col">
        <select onchange="redirectToLink(this)" name="{{ $name }}" id="{{ $name }}" class="w-full form-select bg-white border border-gray-300 text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="#" selected disabled>Select another report</option>
            @foreach($rpLinks as $value)
                @php
                    //$rpFilterLink = $value->getRpFilterLinks?->first();
                @endphp
                <option value="{{$value->report_filter_link_id}}" 
                        data-url="{{route('rp_reports.show', $value->report_filter_link_id)}}"
                        title="#{{ $value->report_filter_link_id}}">
                    {{ $value->title ?? '(No title)' }}                    
                    @if($value)
                        🔗
                    @endif
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

