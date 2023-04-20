@php
$route = $routeName ? route($routeName) : "";
@endphp
<form action="{{$route}}" id="{{$entity}}" method="GET">
    <div class="grid grid-row-1 w-full">
        <div class="grid grid-cols-12 gap-4 items-end">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="updateReport{{Str::ucfirst($typeReport)}}">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            <input type="hidden" name='mode_option' value="{{$modeOption}}">
            <input type="hidden" name='form_type' value="updateParamsReport">
            @foreach($columns as $key =>$value)
            @php
            $title = isset($value['title']) ? $value['title'] : ucwords(str_replace('_', " ", $value['dataIndex']));
            $renderer = $value['renderer'] ?? 'drop_down';
            $name = $value['dataIndex'];
            $date = $itemsSelected['picker_date'] ?? "";
            $allowClear = $value['allowClear'] ?? false;
            $multiple = $value['multiple'] ?? false;
            // dump($multiple, $date, $allowClear)
            @endphp
            <div class="col-span-3">
                @switch($renderer)
                @case("drop_down")
                <x-reports.dropdown7 title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} multiple={{$multiple}} :itemsSelected="$itemsSelected" />
                @break
                @case('picker_date')
                <x-reports.picker-date1 title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} value="{{$date}}" />
                @break
                @default
                @endswitch
            </div>
            @endforeach
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
