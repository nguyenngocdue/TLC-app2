{{-- @dump($rpLinks) --}}
<div class="w-full flex flex-col">
        <select onchange="redirectToLink(this)" name="{{ $name }}" id="{{ $name }}" class="w-full form-select bg-white border border-gray-300 text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            @foreach($rpLinks as $value)
                <option value="{{$linkReports[$value->linked_to_report_id]}}" data-url="{{$linkReports[$value->linked_to_report_id]}}" title="#{{ $value->name }}">
                    {{ $value->title }}
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
    if (url) {
        window.open(url, '_blank');
    }
}
</script>

