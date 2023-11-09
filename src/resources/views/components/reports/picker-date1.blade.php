<div class="text-left whitespace-nowrap">
    <span class="px-1">{{$title}}</span>
</div>
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text border border-gray-300 text-gray-900 rounded-md  dark:placeholder-gray-400 block w-full  text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
            <i class="far fa-calendar-alt"></i>
        </span>
    </div>
    <input name="{{$name}}" value="{{old($name) ? old($name) : $value }}" placeholder={{$singleDatePicker ? "Select a date":"Start date  ->  End date"}} type="text" autocomplete="off" class="form-control float-right bg-white border border-gray-300 text-gray-900 rounded-md p-2.5 dark:placeholder-gray-400 block w-full  text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="{{$name}}">
</div>
    {{-- @if ($errors->any())
            @foreach ($errors->getMessages() as $field => $message)
                @if($field === $name || $field === 'star_date' || $field === 'end_date')
                    <span class="text-xs" role="alert">
                        <p class="mt-1.5 text-red-600 font-semibold" >(Please choose the correct format <strong>{{ucwords(str_replace('_', ' ', $field))}}</strong>)</p>
                    </span>
                    @break
                @endif
            @endforeach
    @endif --}}
<script>
    function initializeDateRangePicker() {

        if ('{{$singleDatePicker}}') {
            $('[id="{{$name}}"]').daterangepicker({
                singleDatePicker: true
                , autoUpdateInput: false
                , locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $('[id="{{$name}}"]').attr("placeholder", "Select a date");
            $('[id="{{$name}}"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });

        } else {
            $('[id="{{$name}}"]').daterangepicker({
                singleDatePicker: false
                , autoUpdateInput: false
                , locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $('[id="{{$name}}"]').attr("placeholder", "Start date  ->  End date");
            $('[id="{{$name}}"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });
        }

        $('[id="{{$name}}"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }

    $(document).ready(function() {
        initializeDateRangePicker();
    });

</script>
