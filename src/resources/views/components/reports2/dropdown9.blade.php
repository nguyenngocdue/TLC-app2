<div class="w-full flex flex-col">
    <div class="text-left whitespace-nowrap">
        <span class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">{!! $title !!}</span>
    </div>
    <form id="myForm" action="{{ $routeName }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="updateReport2">
        <input type="hidden" name="current_mode" value="{{ $currentParams['current_mode'] }}">
        <input type="hidden" name="report_name" value="{{ $currentParams['report_name'] }}">

        <select onchange="submitForm()" name="{{ $name }}" id="{{ $name }}" class="w-full form-select bg-white border border-gray-300 text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="" selected>Select your option...</option>
            @foreach($dataSource as $key => $value)
                <option value="{{ $key }}" {{ $key === $currentParams['current_mode'] ? 'selected' : '' }} title="#{{ $key }}">{{ $value }}</option>
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

{{-- <script type="text/javascript">
    var param = {!! json_encode($itemsSelected[$name]) !!}
    var modeSelect = param.mode_select
    document.addEventListener('DOMContentLoaded', function() {
        if (modeSelect == 1) {
            const year = document.getElementById('name_year');
            const week = document.getElementById('name_weeks_of_year');
            year.style.display = 'none';
            week.style.display = 'none';
        } else {
            const month = document.getElementById('name_month');
            month.style.display = 'none';
        }
    });
</script> --}}

