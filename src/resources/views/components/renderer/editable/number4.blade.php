{{-- @if($readOnly)
<div class="p-2">{{$cell??$slot}}</div>
@endif --}}
<input
    @readonly($readOnly)
    component="editable/number4"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$cell??$slot}}"
    step='any' 
    type="number"
    placeholder="{{$placeholder}}" 
    class="{{$classList}} {{$bgColor}} {{$readOnly?"hidden1":""}}"
    >
<script>
    $("[id='{{$name}}']").on('change', function(e, batchLength){
        @if($onChange)
        {{$onChange}} //<< For order_no column to change line order
        @else
        onChangeDropdown4({
            name:"{{$name}}", 
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            batchLength,
        })
        changeBgColor(this,'{{$table01Name}}')
        @endif
    })
</script>