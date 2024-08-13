{{-- @dump($currentParams) --}}
<div class="w-full flex flex-col">
    <form id="myForm" action="{{ $routeName }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="switchReport2">
        <input type="hidden" name="current_param_report" value="{{ json_encode($paramsCurrentRp) }}">
        <input type="hidden" name="report_id" value="{{$reportId}}">
        <input type="hidden" name="entity_type" value="{{ $entityType}}">
        <input type="hidden" name="entity_type2" value="{{ $entityType2}}">

        <select onchange="submitForm()" name="{{ $name }}" id="{{ $name }}" class="w-full form-select bg-white border border-gray-300 text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            {{-- <option value="" selected >Select your option...</option> --}}
            @foreach($rpLinks as $value)
                <option value="{{ $value->id }}" title="#{{ $value->name }}">{{ $value->title }}</option>
            @endforeach
        </select>
    </form>
</div>

<script type="text/javascript">
    $('#{{$name}}').select2({
        placeholder: ''
        , allowClear: '{{$allowClear}}'
    });

</script>

<script type="text/javascript">
    function submitForm() {
        var form = document.getElementById('myForm'); // Thay 'myForm' bằng ID thực của form
        form.submit();
    }
</script>

