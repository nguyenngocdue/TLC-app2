<select id='{{$id}}' name='{{$name}}' {{$multipleStr}} class='{{$className}}'
onChange='onChangeDropdown4("{{$name}}", "{{$lineType}}", "{{$table01Name}}", "{{$rowIndex}}")'
></select>

<script>
    $(document).ready(()=>{
        const params = { 
            id:'{{$id}}', 
            table01Name: '{{$table01Name}}', 
            selectedJson: '{!! $selected !!}', 
            table: '{{$table}}'
        }
        documentReadyDropdown4(params)
    })
</script>
