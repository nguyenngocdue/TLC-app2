{{-- for Report 2 --}}
<select id='{{$id}}' name='{{$name}}' {{$multipleStr}} {{$readOnly ? "readonly" : ""}} controlType='dropdown'
    allowClear={{$allowClear?'true':'false'}}
    letUserChooseWhenOneItem={{($letUserChooseWhenOneItem??false)?'true':'false'}}
    letUserOpen={{($letUserOpen??false)?'true':'false'}}
    class='{{$classList}}'
    ></select>
<script>
    params2 = {
        id: '{{$id}}'
        , selectedJson: '{!! $selected !!}'
        , table: "{{$table}}"
        , allowClear: {{$allowClear ?'true':'false'}}
        , action: "{{$action ?? 'create'}}"
        , letUserChooseWhenOneItem: {{($letUserChooseWhenOneItem??false) ?'true':'false'}}
        , letUserOpen: {{($letUserOpen??false) ?'true':'false'}}
    }
    documentReadyDropdown2(params2)
    // console.log("Document ready dropdown2")

</script>

<script>
    $("[id='{{$id}}']").on('change', function(e, dropdownParams){
        var inputName = $('[name="{{ $name }}"]');
        var value = inputName.val();
        if (value === '' || (Array.isArray(value) && value.length === 0)) {
            inputName.val(null);
        }
        onChangeDropdown2({
            name:"{{$id}}",
            dropdownParams,
        })
    })
</script>
