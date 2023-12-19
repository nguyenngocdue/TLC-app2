{{-- @if($readOnly)
<div class="p-2">{{$cell??$slot}}</div>
@endif --}}
@php $attributeName = ($control == 'picker_datetime') ? "name1" : "name"; @endphp

<input
    @readonly($readOnly)
    component="editable/{{$control}}"
    {{-- component="editable/picker-all4" --}}
    id="{{$name}}"
    {{$attributeName}}="{{$name}}"
    value="{{$cell??$slot}}"
    type="text"
    placeholder="{{$placeholder}}" 
    style="{{$style}}"
    class="{{$classList}} {{$readOnly?"readonly":""}} {{$readOnly?"hidden1":""}}"
>

@switch($control)
    @case('picker_datetime')
    <input type="hidden" name="{{$name}}" id="hidden_{{$name}}" value="{{$cell??$slot}}"/>
    <script>newFlatPickrDateTime("{{$name}}");</script>
    @break
    @case('picker_date')
    <script>newFlatPickrDate("{{$name}}");</script>
    @break
    @case('picker_time')
    <script>newFlatPickrTime("{{$name}}");</script>
    @break
@endswitch

<script>
    $("[id='{{$name}}']").on('change', function(e, dropdownParams){
        onChangeDropdown4({
            name:"{{$name}}", 
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            dropdownParams,
        });
        changeFooterValue(this,'{{$table01Name}}');
    })
</script>