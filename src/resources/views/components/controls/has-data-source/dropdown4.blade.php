<script>
    params = {
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"

        , lineType : "{{$lineType}}" //<< This is when the form has EditableTable, it get the listenerDataSource from an isolated array element
        , table01Name: "{{$table01Name }}"
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
    reloadDataToDropdown4("{{$id}}", dataSourceDropdown, "{{$table01Name }}", selectedJson)

    $(document).ready(()=>getEById("{{$id}}").trigger('change'))

</script>
