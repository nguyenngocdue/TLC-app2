<script>
    table = "@php echo $table; @endphp"
    k[table] = [{
        id: 1
        , title: "abc"
        , label: "ABC"
    }, {
        id: 2
        , title: "def"
        , label: "DEF"
    }]
    document.write(Dropdown2({
        id: "{{$id}}"
        , name: "{{$name}}"
        , className: "{{$className}}"
        , multipleStr: "{{$multipleStr}}"
        , table: "{{$table}}"
        , selected: "{{$selected}}"
    }))

</script>

<script>
    $(document).ready(function() {
        $('#{{$id}}').select2({
            placeholder: "Please select"
            , allowClear: true
            , templateResult: select2FormatState
        });
    })

</script>
