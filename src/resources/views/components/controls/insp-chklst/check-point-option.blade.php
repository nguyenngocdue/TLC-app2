@once
<script>
const reRenderCheckpoint = (lineId, id) => {
    $("#divSubOptionNCR_" + lineId).hide()
    $("#divSubOptionOnHold_" + lineId).hide()
    const checked = $(`#radio_${lineId}_${id}`).prop('checked')
    // console.log(checked)
    switch(id ){
        case 2:
        case 6: if(checked) $("#divSubOptionNCR_" + lineId).show(); else  $("#divSubOptionNCR_" + lineId).hide();
         break;
        case 4:
        case 8: if(checked) $("#divSubOptionOnHold_" + lineId).toggle(); else $("#divSubOptionOnHold_" + lineId).hide(); break;
        // default: console.log("Unknown option "+id)
    }
    
}

function uncheckOther(lineId, id){
    const array = [1,2,3,4]
    array.splice(array.indexOf(id), 1)
    // console.log(lineId, id, array)

    array.forEach(optionId=>{
        const id = "radio_" + lineId + "_" + optionId
        $(`#${id}`).prop('checked', false)
    })
}

function uncheckMe(lineId, id){
    const checked = $(`#radio_${lineId}_${id}`).prop('checked')
    // console.log("Uncheched me", id, checked)
    const x = "radio_" + lineId + "_" + 0
    if(checked) {
        $(`#${x}`).prop('checked', true)
    } else {
        $(`#${x}`).prop('checked', false)
    }
}

var objIds = {}; 
function updateIdsOfFail(id, name ,valueId,rowIndex,type) {
    //showOrHiddenGroupAttachmentAndComment(valueId,rowIndex,type)
    if (!Object.keys(objIds).includes(name)) {
        objIds[name] = []
        if([2,6].includes(valueId)){
            objIds[name].push(id)
        }
    } else {
        if (objIds[name].includes(id)) {
            const index = objIds[name].indexOf(id);
            objIds[name].splice(index, 1)
        } else {
            if([2,6].includes(valueId)){
                objIds[name].push(id)
            }
        }
    }
    document.getElementById(name).value = objIds[name]        
}
const updateInspectorId = (id) => {
    // console.log(id, name ,valueId,rowIndex,type)
    const inspector_id = "radio_inspector_id_" + id
    document.getElementById(inspector_id).value = {{$cuid}}
}
</script>
@endonce
@php
    $gridCols = isset($options) ? count($options) : 1;
    $cursor = $readOnly ? "cursor-not-allowed" : "cursor-pointer";
@endphp
<div class="grid w-full grid-cols-{{$gridCols}} space-x-2 rounded-xl bg-gray-200 p-2">    
    @php 
        $options = [0=>'ghost', ...$options];
    @endphp
    @foreach($options as $optionId => $option)
        <div class="{{$optionId==0?"hidden":""}}">
            <input type="checkbox"
                name="{{$table01Name}}[{{$keyIdModelControlValue}}][{{$rowIndex}}]" 
                id="radio_{{$line->id}}_{{$optionId}}" 
                class="peer hidden" 
                @checked($line->{$keyIdModelControlValue}==$optionId)                  
                @disabled($readOnly)
                value="{{$optionId}}"
                onclick="
                    reRenderCheckpoint({{$line->id}}, {{$optionId}})
                    updateProgressBar('{{$table01Name}}');
                "
            />
            @if(in_array($option, ['No','Fail']))
                <input id="radio_{{$line->id}}_hidden" name="{{$table01Name}}[control_fail_current_session_ids][{{$rowIndex}}]" type="hidden">
            @endif
            <label for="radio_{{$line->id}}_{{$optionId}}" 
                class="{{$class[$optionId] ?? $class[1]}} {{$cursor}} block select-none rounded-xl p-2 text-center peer-checked:font-bold"
                title="#{{$optionId}}"
                onclick="
                    uncheckMe({{$line->id}}, {{$optionId}});
                    uncheckOther({{$line->id}}, {{$optionId}});
                    updateIdsOfFail({{$line->id}}, 'radio_{{$line->id}}_hidden',{{$optionId}},{{$rowIndex}},'{{$type}}'); 
                    updateInspectorId({{$line->id}});                
                "
                >{{$option}}</label>
        </div>      
    @endforeach
    <input type="hidden" 
        name="{{$table01Name}}[inspector_id][{{$rowIndex}}]"
        id="radio_inspector_id_{{$line->id}}"
        value="{{$line->inspector_id}}"/>
</div>
