@php
    // dump($readOnly);
    $idArray = array_keys($options);
    $gridCols = isset($options) ? count($options) : 1;
    $cursor = $readOnly ? "cursor-not-allowed" : "cursor-pointer";
    $optionA = [0 => (object)["name" => "ghost", "behavior_of" => 0]];
    $options = $optionA + $options;
    // dd($options);
@endphp
<div component="check-point-option" class="grid grid-cols-{{$gridCols}} w-full space-x-2 rounded-xl bg-gray-200 p-2">    
    @foreach($options as $optionId => $option)
        @php
            $color = $option->getColor->name ?? 'gray';
            $class = "peer-checked:bg-$color-300 peer-checked:text-$color-700";
            // dump($option);            
        @endphp
        <div class="{{$optionId==0?"hidden":""}}">
            <input type="checkbox"
                name="{{$table01Name}}[{{$categoryName}}][{{$rowIndex}}]" 
                id="radio_{{$line->id}}_{{$optionId}}" 
                class="peer hidden" 
                @checked($line->{$categoryName}==$optionId)                  
                @disabled($readOnly)
                value="{{$optionId}}"
                data-behavior-id="{{$option->behavior_of}}"
                @if(!$readOnly)
                    onclick="
                        reRenderCheckpoint2({{$line->id}}, {{$optionId}}, {{$option->behavior_of}});
                        updateProgressBar2('{{$table01Name}}');
                    "
                @endif
            />

            <label for="radio_{{$line->id}}_{{$optionId}}" 
                class="{{$class}} {{$cursor}} block select-none rounded-xl p-2 text-center peer-checked:font-bold"
                title="#{{$optionId}}"
                @if(!$readOnly)
                    onclick="
                        uncheckMe2({{$line->id}}, {{$optionId}});
                        uncheckOther2(@json($idArray), {{$line->id}}, {{$optionId}});
                        updateInspectorId2({{$line->id}}, {{$cuid}});
                    "
                @endif
                >
                {{$option->name}}
            </label>
        </div>      
    @endforeach
</div>
