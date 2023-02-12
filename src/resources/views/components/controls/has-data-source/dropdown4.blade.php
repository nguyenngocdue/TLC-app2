<script>
    id = "{{$id}}"
    table01Name = "{{$table01Name }}"
    params = {
        id
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"

        , lineType : "{{$lineType}}" //<< This is when the form has EditableTable, it get the listenerDataSource from an isolated array element
        , table01Name 
        , rowIndex: "{{$rowIndex}}"
    }
    // console.log(params)
    document.write(Dropdown4(params))

    selectedJson = '{!! $selected !!}'
    // console.log(selectedJson)
    selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    // console.log(selectedJson)
    selectedJson = JSON.parse(selectedJson)
    // console.log(selectedJson)
    table = "{{$table}}"
    dataSourceDropdown = k[table];
    // console.log("DataSource from",table)
    if(dataSourceDropdown === undefined) console.error("Key {{$table}} not found in k[]");
    // console.log("{{$id}}")
    reloadDataToDropdown4(id, dataSourceDropdown, table01Name, selectedJson)

    $(document).ready(()=>{
        id = "{{$id}}"
        table01Name = "{{$table01Name}}"
        // console.log(listenersOfDropdown4s,table01Name )
        listenersOfDropdown4s[table01Name].forEach((listener)=>{
            const fieldName = getFieldNameInTable01FormatJS(id, table01Name)
            // console.log(listener, id, fieldName,listener.triggers)

            if(listener.triggers.includes(fieldName) && listener.listen_action === 'reduce'){
                // console.log("I am a trigger of reduce, I have to trigger myself when form load ",id )
                getEById(id).trigger('change')
            }
        })
    })

</script>
