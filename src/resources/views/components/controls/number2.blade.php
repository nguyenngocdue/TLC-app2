<input
    component="controls/number2" 
    id="{{$name}}" 
    name="{{$name}}" 
    step='any' 
    type='text' 
    {{$readOnly ? 'readonly' : ''}}
    value='{{old($name, $value)}}' 
    {{-- onchange='onChangeDropdown2({name: "{{$name}}"})' --}}
    onchange="onChangeDropdown2({name: '{{$name}}' })"
    class='{{$classList}} {{$readOnly ? 'readonly' : ''}}'
    >

<script>
    parseNumber2(@json($name), @json(old($name, $value)),@json($numericScale));
</script>
