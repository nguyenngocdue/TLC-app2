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
    @if($debug) Line ID @endif
    <input class="w-28" type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[id][{{$line->id}}]" 
    value="{{$line->id}}">
    @if($debug) Inspector ID @endif
    <input class="w-28" type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[inspector_id][{{$line->id}}]" id="inspector_id_{{$line->id}}"
        value="{{$line->inspector_id}}"/>
    @if($debug) Attachment Is Group @endif
    <input class="w-28" type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[attachment_is_grouped][{{$line->id}}]"
        value="{{$line->attachment_is_grouped}}"/>

    <div component="check-point" class="p-1 sm:p-2">
        <div class="flex justify-center mb-2">
            @switch($line->getControlType->slug)
                @case ("text") 
                    <x-controls.insp_chklst.check-point-text2 readOnly="{{$readOnly}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
                @break
                @case ("textarea")
                    <x-controls.insp_chklst.check-point-textarea2 readOnly="{{$readOnly}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
                @break
                @case ("radio") 
                    <div class="w-full">
                        <x-controls.insp_chklst.check-point-option2 readOnly="{{$readOnly}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" type="{{$type}}" categoryName="{{$categoryName}}" />
                        <x-controls.insp_chklst.check-point-create-ncr-on-hold2 readOnly="{{$readOnly}}" :line="$line" :checkPointIds="$checkPointIds" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
                    </div>
                @break
                @case ("signature")
                    <x-controls.insp_chklst.check-point-signature readOnly="{{!$checkPointSignable}}" :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug" />
                @break
                @default
                    <div class="border bg-pink-400 p-2 rounded w-full text-center font-bold">
                        Unknown control_type_id [#{{$line->control_type_id}}] ({{$line->getControlType->name}})
                    </div>
                @break
            @endswitch
        </div>

        @if($type == 'qaqc_insp_chklst_lines')
            <div component="group_attachment_comment_{{$rowIndex}}">            
                @if($line->getControlType->slug == "radio") 
                    <x-renderer.attachment-group                 
                        name="{{$table01Name}}[insp_photos][{{$rowIndex}}]" 
                        readOnly="{{!$allowToUpload}}"
                        destroyable="{{$destroyable}}"

                        :value="$attachments" 
                        :properties="$attachmentProperties"
                        :groups="$groups"
                        />
                    <br />
                @endif
                <x-controls.comment.comment-group2a 
                    readOnly="{{!$allowToComment}}" 
                    category="insp_comments" 
                    commentableType="{{$type}}" 
                    commentableId="{{$line->id}}" 
                    
                    :commentIds="$checkPointIds" 
                    />
            </div>
        @endif
    </div>
</x-renderer.card>
