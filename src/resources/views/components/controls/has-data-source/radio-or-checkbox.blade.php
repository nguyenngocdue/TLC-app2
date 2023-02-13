<script>
    params = {
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multiple: {{$multiple}}
        , span: {{$span}}
    }
    // console.log(params)
    document.write(RadioOrCheckbox(params))

    selectedJson = '{!! $selected !!}'
    // console.log(selectedJson)
    selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    // console.log(selectedJson)
    selectedJson = JSON.parse(selectedJson)
    // console.log(selectedJson)
    table = "{{$table}}"
    dataSourceDropdown = k[table];
    // console.log("DataSource from",table)
    if(dataSourceDropdown === undefined) console.error("key {{$table}} not found in k[]");
    reloadDataToDropdown2("{{$id}}", dataSourceDropdown, selectedJson)
    
    $(document).ready(()=>{
        listenersOfDropdown2.forEach((listener)=>{
            if(listener.triggers.includes("{{$id}}") && listener.listen_action === 'reduce'){
                // console.log("I am a trigger of reduce, I have to trigger myself when form load [{{$id}}]", )
                getEById("{{$id}}").trigger('change')
            }
        })
    })

</script>
