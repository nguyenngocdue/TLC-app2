<input
    @readonly($readOnly)
    component="editable/number"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$cell??$slot}}"
    step='any' 
    type="number"
    placeholder="{{$placeholder}}" 
    class="{{$classList}} {{$bgColor}}"
    onChange="{!! $onChange !!};changeBgColor(this,'{{$table01Name}}')"
    >