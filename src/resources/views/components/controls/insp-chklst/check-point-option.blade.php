@once
<script>
function initClick(lineId, checkedId){
    // console.log("Clicked on", lineId, checkedId)
    $("#radio_" + lineId + "_" + checkedId).trigger('click')
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
</script>
@endonce

<div class="grid w-full grid-cols-4 space-x-2 rounded-xl bg-gray-200 p-2">
    @foreach($options as $id => $option)
        <div>
            <input type="radio" 
                    name="{{$table01Name}}[qaqc_insp_control_value_id][{{$rowIndex}}]" 
                    id="radio_{{$line->id}}_{{$id}}" 
                    class="peer hidden" 
                    @checked($line->{"qaqc_insp_control_value_id"}==$id)  
                    value="{{$id}}"
                    />
            <label for="radio_{{$line->id}}_{{$id}}" 
                class="{{$class[$id] ?? $class[1]}} block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:font-bold 1peer-checked:text-white"
                title="#{{$id}}"
                >{{$option}}</label>
        </div>
        <script>
            registerListen({{$line->id}}, {{$id}})
        </script>
    @endforeach
</div>
