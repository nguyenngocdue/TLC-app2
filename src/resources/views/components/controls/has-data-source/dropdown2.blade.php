<script>
    document.write(Dropdown2({
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"
        , table: "{{$table}}"
        , selected: "{{$selected}}"
    }))
    $(document).ready(function() {
        $('#{{$id}}').select2({
            placeholder: "Please select"
            , allowClear: true
            , templateResult: select2FormatState
        });
    })

</script>
