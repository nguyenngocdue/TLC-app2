<select id='{{$id}}' name='{{$name}}' {{$multipleStr}} class='{{$className}}'
onChange='onChangeDropdown4({name:"{{$name}}", lineType:"{{$lineType}}", table01Name:"{{$table01Name}}", rowIndex:{{$rowIndex}}, saveOnChange:{{$saveOnChange?1:0}}})'
></select>

<script>
    $(document).ready(()=>{
        const params = {id:'{{$id}}', table01Name: '{{$table01Name}}', selectedJson: '{!! $selected !!}', table: '{{$table}}'}
        documentReadyDropdown4(params)
    })
</script>
