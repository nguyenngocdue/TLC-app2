<select id='{{$id}}' name='{{$name}}' {{$multipleStr}} class='{{$className}}'
onChange='onChangeDropdown4("{{$name}}", "{{$lineType}}", "{{$table01Name}}", "{{$rowIndex}}")'
></select>

<script>
    $(document).ready(()=>{
        id = "{{$id}}"
        if(runOnce[id]){
            // console.log("cancel reload")
            getEById(id).select2({
                placeholder: "Please select"
                // , allowClear: true //<<This make a serious bug when user clear and re-add a multiple dropdown, it created a null element
                , templateResult: select2FormatState
            });
            return
        }
        runOnce[id] = true
         
        table01Name = "{{$table01Name }}"
        selectedJson = '{!! $selected !!}'
        selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
        selectedJson = JSON.parse(selectedJson)
        dataSourceDropdown = k["{{$table}}"];
        if(dataSourceDropdown === undefined) console.error("Key {{$table}} not found in k[]");
        reloadDataToDropdown4(id, dataSourceDropdown, table01Name, selectedJson)
        listenersOfDropdown4s[table01Name].forEach((listener)=>{
            const fieldName = getFieldNameInTable01FormatJS(id, table01Name)
            if(listener.triggers.includes(fieldName) && listener.listen_action === 'reduce'){
                // console.log("I am a trigger of reduce, I have to trigger myself when form load ",id )
                getEById(id).trigger('change')
            }
        })
    })

</script>
