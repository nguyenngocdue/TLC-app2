@once
<script>
function initClick(lineId, checkedId){
    // console.log("Clicked on", lineId, checkedId)
    if(checkedId !== undefined){
        const target = "#radio_" + lineId + "_" + checkedId
        $(target).trigger('click')
    }
}

function registerListen(lineId, id){
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
    }
    $("#radio_" + lineId + "_" + id).click(reRender)
}
    var objIds = {}; 
    function updateIdsOfFail(id, name ,valueId) {
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
</script>
@endonce
@php
    $gridCols = isset($options) ? count($options) : 1;
@endphp
<div class="grid w-full grid-cols-{{$gridCols}} space-x-2 rounded-xl bg-gray-200 p-2">
    @foreach($options as $id => $option)
        <div>
            <input type="radio" 
                    name="{{$table01Name}}[{{$keyIdModelControlValue}}][{{$rowIndex}}]" 
                    id="radio_{{$line->id}}_{{$id}}" 
                    class="peer hidden" 
                    @checked($line->{$keyIdModelControlValue}==$id)  
                    value="{{$id}}"
                    />
                    @if(in_array($option, ['No','Fail']))
                    <input id="radio_{{$line->id}}_hidden" name="{{$table01Name}}[control_fail_current_session_ids][{{$rowIndex}}]" type="hidden">
                    @endif
            <label for="radio_{{$line->id}}_{{$id}}" 
                class="{{$class[$id] ?? $class[1]}} block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:font-bold 1peer-checked:text-white"
                title="#{{$id}}"
                onclick="updateIdsOfFail({{$line->id}}, 'radio_{{$line->id}}_hidden',{{$id}})"
                >{{$option}}</label>
        </div>
        <script>
            registerListen({{$line->id}}, {{$id}})
        </script>
    @endforeach
</div>
