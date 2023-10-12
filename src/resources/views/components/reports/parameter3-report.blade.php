@php
$route = $routeName ? route($routeName) : "";
@endphp
<form action="{{$route}}" id="{{$entity}}" method="GET">
    <div class="grid grid-row-1 w-full">
        <div class="grid grid-cols-12 gap-4 items-baseline">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="updateReport{{Str::ucfirst($typeReport)}}">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            <input type="hidden" name='mode_option' value="{{$modeOption}}">
            <input type="hidden" name='form_type' value="updateParamsReport">
            <input type="hidden" name='optionPrintLayout' value="{{$optionPrint}}">
            @if (!reset($columns))
            <div class="col-span-12">
                <x-feedback.alert type='warning' message="There are no filters to render here."></x-feedback.alert>
            </div>
            @else
            @foreach($columns as $key =>$value)
            @php
            $title = isset($value['title']) ? $value['title'] : ucwords(str_replace('_', " ", $value['dataIndex']));
            $renderer = $value['renderer'] ?? 'drop_down';
            $name = $value['dataIndex'];
            $date = $itemsSelected['picker_date'] ?? "";
            $allowClear = $value['allowClear'] ?? false;
            $multiple = $value['multiple'] ?? false;
            $hasListenTo = $value['hasListenTo'] ?? false;
            $singleDatePicker = $value['singleDatePicker'] ?? false;
            // dump($multiple, $date, $allowClear)
            @endphp
            <div class="col-span-3">
                @switch($renderer)
                @case("drop_down")
                <x-reports.dropdown7 hasListenTo={{$hasListenTo}} title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} multiple={{$multiple}} :itemsSelected="$itemsSelected" />
                    @if ($errors->any())
                            @foreach ($errors->getMessages() as $field => $message)
                                @if($field === $name)
                                <span class="text-xs" role="alert">
                                    <ul class="mt-1.5 text-red-600 font-semibold">
                                        <li>{{last($message)}}</li>
                                    </ul>
                                </span>
                                @endif
                            @endforeach
                    @endif
                @break
                @case('picker_date')
                <x-reports.picker-date1 title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} value="{{$date}}" singleDatePicker='{{$singleDatePicker}}' />
                @break
                @default
                @endswitch
            </div>
            @endforeach
            @endif
        </div>
    </div>
    <div class="py-2">
        <x-renderer.button htmlType="submit" type="primary"><i class="fa-sharp fa-solid fa-check"></i> Apply Filter</x-renderer.button>
        <x-renderer.button htmlType="submit" click="resetFilter()" type="secondary"><i class="fa-sharp fa-solid fa-circle-xmark pr-1"></i>Reset</x-renderer.button>
    </div>
</form>

<script type="text/javascript">
    function resetFilter() {
        $('[id="' + "{{$entity}}" + '"]').append('<input type="hidden" name="form_type" value="resetParamsReport">')
    }

</script>

{{-- <script>
        // This script runs after the HTML document has fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Check if a "reload" cookie exists
            const reloadCookie = getCookie("reload");

            // If the "reload" cookie does not exist (page was not manually reloaded)
            if (!reloadCookie) {
                // Submit the form with the ID specified in the {{$entity}} variable
document.getElementById("{{$entity}}").submit();

// Create a "reload" cookie with a value of "1" (expires in 1 minute)
setCookie("reload", "1", 1);
}
});

// Function to retrieve the value of a specific cookie
function getCookie(name) {
const value = `; ${document.cookie}`;
const parts = value.split(`; ${name}=`);
if (parts.length === 2) return parts.pop().split(';').shift();
}

// Function to create a new cookie
function setCookie(name, value, minutes) {
const date = new Date();
date.setTime(date.getTime() + (minutes * 60 * 1000));
const expires = "expires=" + date.toUTCString();
document.cookie = name + "=" + value + ";" + expires + ";path=/";
}
</script> --}}
