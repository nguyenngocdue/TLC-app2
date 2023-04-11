@if($readOnly)
@php
$selectedDecode =json_decode($selected);
$label = DB::table($table)->whereIn('id',$selectedDecode)->pluck('name');
@endphp
@if($multipleStr)
<span class="select2 select2-container select2-container--default w-full">
    <span class="selection">
        <span class="select2-selection select2-selection--multiple {{$readOnly ? 'readonly' : ''}}">
            <ul class="select2-selection__rendered">
                @forelse($selectedDecode as $key => $value)
                <input type="hidden" id="{{$id}}" name="{{$name}}" value="{{$value}}" class='{{$classList}} {{$readOnly ? 'readonly' : ''}}' {{$readOnly ? 'readonly' : ''}} />
                <li class="select2-selection__choice">{{$label[$key]}}</li>
                @empty
                <div class="p-3"></div>
                @endforelse
            </ul>
            {{-- <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> --}}
        </span>
    </span>
</span>
@else
{{-- @dd($label[0]) --}}
<div class="flex-shrink-0 inline-flex justify-between {{$classList}} {{$readOnly ? 'readonly' : ''}}">
    <input type="hidden" id="{{$id}}" name="{{$name}}" value="{{$selectedDecode[0] ?? ''}}" class='{{$classList}} {{$readOnly ? 'readonly' : ''}}' {{$readOnly ? 'readonly' : ''}} />
    <span>{{$label[0]??""}}</span>
    <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
    </svg>
</div>
@endif

@else

<select id='{{$id}}' name='{{$name}}' allowClear='{{$allowClear ?'true':'false'}}' onChange='onChangeDropdown2("{{$name}}")' {{$multipleStr}} controlType='dropdown' class='{{$classList}}'></select>

<script>
    params2 = {
        id: '{{$id}}'
        , selectedJson: '{!! $selected !!}'
        , table: "{{$table}}"
        , allowClear: {{$allowClear ?'true':'false'}}
    }
    documentReadyDropdown2(params2)

</script>
@endif
