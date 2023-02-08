<script>
    document.write(Dropdown2({
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"
    }))
    reloadDataToDropdown2("{{$id}}", k["{{$table}}"], JSON.parse("{{$selected}}"))

    $(document).ready(function() {
        getEById("{{$id}}").trigger('change')
    })

</script>
