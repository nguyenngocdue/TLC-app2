{{-- @if($readOnly)
<div class="p-2">{{$cell??$slot}}</div>
@endif --}}
@php
    $value = json_decode($cell??$slot);
@endphp
<input
    @readonly($readOnly)
    component="editable/number4"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$value}}"
    step='any' 
    type="text"
    placeholder="{{$placeholder}}" 
    class="{{$classList}} {{$bgColor}} {{$readOnly?"hidden1":""}}"
    >
<script>
    $("[id='{{$name}}']").on('change', function(e, dropdownParams){
        @if($onChange)
        {{$onChange}} /* << For order_no column to change line order */
        @else
        onChangeDropdown4({
            name:"{{$name}}", 
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            dropdownParams,
        });
        changeBgColor(this,'{{$table01Name}}');
        changeFooterValue(this,'{{$table01Name}}');
        @endif
    })
</script>
<script>
    parseNumber2(@json($name), @json($value));
</script>