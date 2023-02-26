<script>
    params = {
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"
    }
    // console.log(params)
    document.write(Dropdown2(params))
    
    params2 = {id: '{{$id}}',selectedJson: '{!! $selected !!}',table: "{{$table}}" }
    documentReadyDropdown2(params2)
    
</script>
{{-- , saveOnChange: "{{$saveOnChange?1:0}}" --}}