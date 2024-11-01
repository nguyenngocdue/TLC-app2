@once
<script>
const idArray = [1,2,3,4];
</script>
@endonce
@php
    $gridCols = isset($options) ? count($options) : 1;
    $cursor = $readOnly ? "cursor-not-allowed" : "cursor-pointer";
@endphp
<div component="check-point-option" class="grid grid-cols-{{$gridCols}} w-full space-x-2 rounded-xl bg-gray-200 p-2">    
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
                    uncheckOther(idArray, {{$line->id}}, {{$optionId}});
                    updateIdsOfFail({{$line->id}}, 'radio_{{$line->id}}_hidden',{{$optionId}},{{$rowIndex}},'{{$type}}'); 
                    updateInspectorId({{$line->id}}, {{$cuid}});                
                "
                >{{$option}}</label>
        </div>      
    @endforeach

    <input
        type="hidden"
        name="{{$table01Name}}[inspector_id][{{$rowIndex}}]"
        id="radio_inspector_id_{{$line->id}}"
        value="{{$line->inspector_id}}"/>
</div>
