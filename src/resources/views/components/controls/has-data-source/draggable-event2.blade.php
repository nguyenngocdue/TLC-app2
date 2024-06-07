<div id='{{$id}}' 
    name='{{$multiple?$name."[]":$name}}' 
    {{-- onChange='onChangeDropdown2({name: "{{$id}}"})'  --}}
    onChange="onChangeDropdown2({name: '{{$id}}' })"
    {{($multiple) ? 'multiple' : ''}} 
    controlType='draggable_event' 
    colSpan='col-span-{{$span??4}}' 
    {{$readOnly ? 'readOnly' : ''}}
    class='{{$classList}}'
></div>

<script>
    // params2 = {
    //     id: '{{$id}}'
    //     , selectedJson: '{!! $selected !!}'
    //     , table: "{{$table}}" 
    //     , action: "{{$action ?? 'create'}}"
    // }
    // documentReadyDropdown2(params2)

</script>
