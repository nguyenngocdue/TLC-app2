<script>
    params = {
        id: "{{$id}}", 
        name: "{{$name}}", 
        className: "{{$className}}", 
        multiple: {{$multiple}}, 
        span: {{$span}}, 
        saveOnChange:{{$saveOnChange?1:0}},
        readOnly: "{{$readOnly}}",
    }
    // console.log(params)
    document.write(RadioOrCheckbox(params))

    params2 = {id: '{{$id}}',selectedJson: '{!! $selected !!}',table: "{{$table}}" }
    documentReadyDropdown2(params2)

</script>
