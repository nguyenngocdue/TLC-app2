{{-- @if($readOnly)
<div class="p-2">{{$cell??$slot}}</div>
@endif --}}
<input
    @readonly($readOnly)
    component="editable/picker-all4"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$cell??$slot}}"
    type="text"
    placeholder="{{$placeholder}}" 
    class="{{$classList}} {{$readOnly?"readonly":""}} {{$readOnly?"hidden1":""}}"
>
<script>
    $("[id='{{$name}}']").on('change', function(e, dropdownParams){
        onChangeDropdown4({
            name:"{{$name}}", 
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            dropdownParams,
        })
    })
</script>