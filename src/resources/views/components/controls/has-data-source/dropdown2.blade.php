<script>
    params = {
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"

        , lineEntity : "{{$lineEntity}}" //<< This is when the form has EditableTable, it get the listenerDataSource from an isolated array element
        , isInTable: {{$isInTable}}
    }
    // console.log(params)
    document.write(Dropdown2(params))

    selectedJson = '{!! $selected !!}'
    // console.log(selectedJson)
    selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    // console.log(selectedJson)
    selectedJson = JSON.parse(selectedJson)
    // console.log(selectedJson)
    table = "{{$table}}"
    dataSourceDropdown2 = k[table];
    // console.log("DataSource from",table)
    if(dataSourceDropdown2 === undefined) console.error("key {{$table}} not found in k[]");
    reloadDataToDropdown2("{{$id}}", dataSourceDropdown2, "{{$lineEntity}}", selectedJson)

    $(document).ready(()=>getEById("{{$id}}").trigger('change'))

</script>
