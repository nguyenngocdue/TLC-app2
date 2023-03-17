<input
    @readonly($readOnly)
    component="editable/text4"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$cell??$slot}}"
    type="text"
    placeholder="{{$placeholder}}" 
    class="{{$classList}} {{$readOnly?"readonly":""}}"
    {{-- onChange='onChangeDropdown4({name:"{{$name}}", table01Name:"{{$table01Name}}", rowIndex:{{$rowIndex}}, saveOnChange: {{$saveOnChange?1:0}}})' --}}
    >
<script>
    $("[id='{{$name}}']").on('change', function(e, batchLength){
        onChangeDropdown4({
            name:"{{$name}}", 
            table01Name:"{{$table01Name}}", 
            rowIndex: {{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            batchLength,
        })
    })
</script>