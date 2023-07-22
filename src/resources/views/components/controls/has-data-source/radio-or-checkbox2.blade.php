<div id='{{$id}}' 
    name='{{$multiple?$name."[]":$name}}' 
    onChange='onChangeDropdown2("{{$id}}")' 
    {{($multiple) ? 'multiple' : ''}} 
    controlType='radio_or_checkbox' 
    colSpan='col-span-{{$span??4}}' 
    {{$readOnly ? 'readOnly' : ''}}
    class='{{$classList}}'
></div>
<script>
    params2 = {
        id: '{{$id}}'
        , selectedJson: '{!! $selected !!}'
        , table: "{{$table}}" 
        , action: "{{$action ?? 'create'}}"
        , letUserChooseWhenOneItem: {{($letUserChooseWhenOneItem??false)?'true':'false'}}
    }
    documentReadyDropdown2(params2)

</script>
