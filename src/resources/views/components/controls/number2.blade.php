<input
    component="controls/number2" 
    id="{{$name}}" 
    name="{{$name}}" 
    step='any' 
    type='number' 
    {{$readOnly ? 'readonly' : ''}}
    value='{{old($name, $value)}}' 
    onchange='onChangeDropdown2("{{$name}}")'
    class='{{$classList}} {{$readOnly ? 'readonly' : ''}}'
    >
