<div class="w-full flex flex-col">
    <div class="text-left whitespace-nowrap">
        <span class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">{!!$title!!}</span>
    </div>
    {{-- @dd($itemsSelected) --}}
    <form id="myForm" action="{{$routeName}}" method="GET">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="switchParamsReport">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            <input type="hidden" name='mode_option' value="{{$modeOption}}">
            <input type="hidden" name='form_type' value="updateParamsReport">

        <select  onchange="submitForm()" name='{{$name}}' id="{{$name}}" class="w-full form-select  bg-white border border-gray-300  text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option class="py-10 w-full " value="" selected>Select your option...</option>
            @foreach($dataSource as $key => $value)
            @php
            $value = str_replace('_', " ", $value);
            $selected = "";
            if (isset($itemsSelected[$name])) {
            if (is_numeric($key) && is_numeric($itemsSelected[$name])) {
                $selected = $key*1 === $itemsSelected[$name]*1 ? "selected" :"";
                } else{
                $selected = $key === $itemsSelected[$name] ? "selected" :"";
                }
            }
            @endphp
            <option class="py-10 w-full " value="{{$key}}" {{$selected}} title="#{{$key}}">{{$value}}</option>
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

