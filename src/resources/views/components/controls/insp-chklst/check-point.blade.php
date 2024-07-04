{{-- @dump($readOnly) --}}
<x-renderer.card class="border my-1 mt-2 bg-white p-0">
    <div class="bg-gray-300 rounded-t flex gap-2">
        <div class="flex items-center font-bold bg-gray-700 text-white rounded-tl px-3 " >
            {{$index}}
        </div>
        <div class="p-1">
            <p title="#{{$line->id}}">{{$line->name}}</p>
            <p title="Vietnamese"><i>{{$line->description}}</i></p>
        </div>
    </div>
    <input type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[id][{{$rowIndex}}]" value="{{$line->id}}">

    <div class="p-4">
        <div class="flex justify-center mb-2">
            @switch($line->control_type_id)
            @case (1) {{-- 1 => "text" --}}
            <x-controls.insp_chklst.check-point-text readOnly="{{$readOnly}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
            @break
            @case (4) {{-- 4 => "radio" --}}
            <div class="w-full">
                <x-controls.insp_chklst.check-point-option readOnly="{{$readOnly}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" type="{{$type}}" />
                <x-controls.insp_chklst.check-point-create-ncr-on-hold readOnly="{{$readOnly}}" :line="$line" :checkPointIds="$checkPointIds" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
            </div>
            @break
            @case (7) {{-- 7 => "signature" --}}
            <x-controls.insp_chklst.check-point-signature readOnly="{{$readOnly}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
            @break
            @default
            Unknown control_type_id {{$line->control_type_id}} ({{$controlType[$line->control_type_id]}})
            @break
            @endswitch
        </div>
    
        <div id="group_attachment_comment_{{$rowIndex}}" @class([ 'hidden'=> $type == 'hse_insp_chklst'
            ])>
            <x-renderer.attachment2 
                name="{{$table01Name}}[insp_photos][{{$rowIndex}}]" 
                :value="$attachments" 
                :properties="$props['props']['_insp_photos']['properties']"
                readOnly="{{$readOnly}}"
                destroyable="{{$destroyable}}"
                />
            <br />
            <x-controls.comment.comment-group2a readOnly="{{$readOnly}}" :commentIds="$checkPointIds" category="insp_comments" commentableType="{{$type}}" commentableId="{{$line->id}}" />
        </div>
    </div>
</x-renderer.card>
