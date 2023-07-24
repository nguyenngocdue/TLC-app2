{{-- @php $selectedDecode =json_decode($selected); @endphp --}}

<select id='{{$id}}' name='{{$name}}' {{$multipleStr}} {{$readOnly ? "readonly" : ""}} controlType='dropdown' 
    onChange='onChangeDropdown2("{{$id}}")' 
    allowClear={{$allowClear?'true':'false'}} 
    letUserChooseWhenOneItem={{($letUserChooseWhenOneItem??false)?'true':'false'}} 
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
    }
    documentReadyDropdown2(params2)
    // console.log("Document ready dropdown2")

</script>

