@php
$route = $routeName ? route($routeName) : "";
//dump($modeSelect)
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
            {{-- set value when param has slect mode --}}
            <input type="hidden" name='children_mode' value="{{$childrenMode}}">
            
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
            $firstHidden = isset($value['firstHidden']) ? 'hidden' : ''?? '';
            @endphp
            <div id="name_{{$name}}" class="col-span-3 {{$name}} {{$firstHidden}}">
                @switch($renderer)
                @case("drop_down")
                <x-reports.dropdown7 :infoParam="$value" hasListenTo={{$hasListenTo}} title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} multiple={{$multiple}} :itemsSelected="$itemsSelected" />
                    @if ($errors->any())
                            @foreach ($errors->getMessages() as $field => $message)
                                @if($field === $name && $field !== 'end_date' && $field !== 'end_date')
                                    <span class="text-xs" role="alert">
                                        <p class="mt-1.5 text-red-600 font-semibold">(Please choose the correct format <strong>{{ucwords(str_replace('_', ' ', $field))}}</strong>)</p>
                                    </span>
                                @endif
                            @endforeach
                    @endif
                @break
                @case('picker_date')
                <x-reports.picker-date1 title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} value="{{$date}}" singleDatePicker='{{$singleDatePicker}}' />
                      @if ($errors->any())
                            @foreach ($errors->getMessages() as $field => $message)
                                @if($field === 'start_date' || $field === 'end_date')
                                 <span class="text-xs" role="alert">
                                     <p class="mt-1.5 text-red-600 font-semibold">(Please choose the correct format <strong>{{ucwords(str_replace('_', ' ', $field))}}</strong>)</p>
                                 </span>
                                @endif
                            @endforeach
                    @endif
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
