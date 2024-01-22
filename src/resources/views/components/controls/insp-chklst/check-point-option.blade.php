@once
<script>
function initClick(lineId, checkedId){
    // console.log("Clicked on", lineId, checkedId)
    if(checkedId !== undefined){
        const target = "#radio_" + lineId + "_" + checkedId
        $(target).trigger('click')
    }
}

function registerCheckpointListener(lineId, id){
    const reRender = ()=>{
        // console.log("Clicked radio_" + lineId + "_" + id, id)
        $("#divSubOptionNCR_" + lineId).hide()
        $("#divSubOptionOnHold_" + lineId).hide()
        switch(id ){
            // case 1, 5: break;
            case 2:
            case 6: $("#divSubOptionNCR_" + lineId).show(); break;
            // case 3, 7: break;
            case 4:
            case 8: $("#divSubOptionOnHold_" + lineId).show(); break;
            // default: console.log("Unknown option "+id)
        }
        updateProgressBar('{{$table01Name}}');
    }
    $("#radio_" + lineId + "_" + id).click(reRender)
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
    @foreach($options as $optionId => $option)
        <div>
            <input type="radio" 
                name="{{$table01Name}}[{{$keyIdModelControlValue}}][{{$rowIndex}}]" 
                id="radio_{{$line->id}}_{{$optionId}}" 
                class="peer hidden" 
                @checked($line->{$keyIdModelControlValue}==$optionId)  
                @disabled($readOnly)
                value="{{$optionId}}"
            />
            @if(in_array($option, ['No','Fail']))
            <input id="radio_{{$line->id}}_hidden" name="{{$table01Name}}[control_fail_current_session_ids][{{$rowIndex}}]" type="hidden">
            @endif
            <label for="radio_{{$line->id}}_{{$optionId}}" 
                class="{{$class[$optionId] ?? $class[1]}} {{$cursor}} block select-none rounded-xl p-2 text-center peer-checked:font-bold 1peer-checked:text-white"
                title="#{{$optionId}}"
                onclick="
                updateIdsOfFail({{$line->id}}, 'radio_{{$line->id}}_hidden',{{$optionId}},{{$rowIndex}},'{{$type}}'); 
                updateInspectorId({{$line->id}});                
                "
                >{{$option}}</label>
        </div>
        <script>
            registerCheckpointListener({{$line->id}}, {{$optionId}})
        </script>
    @endforeach
    <input type="hidden" 
        name="{{$table01Name}}[inspector_id][{{$rowIndex}}]"
        id="radio_inspector_id_{{$line->id}}"
        value="{{$line->inspector_id}}"/>
</div>
