
    <select id='{{$id}}' name='{{$name}}' {{$multipleStr}} {{$readOnlyStr}} class='{{$classList}}'
    ></select>
    <script>
        $(document).ready(()=>{
            const params = {id:'{{$id}}', table01Name: '{{$table01Name}}', selectedJson: '{!! $selected !!}', table: '{{$table}}', batchLength: {{$batchLength}}}
            documentReadyDropdown4(params)
            // console.log("Document ready dropdown4")
        })
    </script>


<script>
    $("[id='{{$name}}']").on('change', function(e, batchLength){
        onChangeDropdown4({
            name:"{{$name}}", 
            lineType:"{{$lineType}}",
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            batchLength,
        })
    })
</script>