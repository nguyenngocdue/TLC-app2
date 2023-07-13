<input
    component="controls/number2" 
    id="{{$name}}" 
    name="{{$name}}" 
    step='any' 
    type='text' 
    {{$readOnly ? 'readonly' : ''}}
    value='{{old($name, $value)}}' 
    onchange='onChangeDropdown2("{{$name}}")'
    class='{{$classList}} {{$readOnly ? 'readonly' : ''}}'
    >
<script>
    var id = @json($name);
    let initValue = @json(old($name, $value));
    const inputNumber = document.getElementById(id);
    const formatterSubmitFn = value => value.replace(/,/g, '');
    const formatterFn = (value) => {
        const [a, b] = value.split(".")
    return a.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + (typeof b == 'string' ? ('.' + b) : "")
    }
    const parserFn = value => value.replace(/\$\s?|(,*)/g, '');
    inputNumber.addEventListener('input', event => {
    const inputValue = event.target.value;
    const parsedValue = parserFn(inputValue);
    if (!isNaN(parsedValue)) {
        event.target.value = formatterFn(parsedValue);
    }
    });
    inputNumber.value = formatterFn(initValue);
</script>
