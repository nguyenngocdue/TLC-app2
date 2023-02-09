<script>
    document.write(Dropdown2({
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"
    }))

    selectedJson = '{!! $selected !!}'
    // console.log(selectedJson)
    selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    // console.log(selectedJson)
    selectedJson = JSON.parse(selectedJson)
    // console.log(selectedJson)
    reloadDataToDropdown2("{{$id}}", k["{{$table}}"], selectedJson)

    $(document).ready(()=>getEById("{{$id}}").trigger('change'))

</script>
