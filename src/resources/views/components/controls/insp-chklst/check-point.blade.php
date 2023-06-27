<x-renderer.card class="border my-1 mt-2 bg-white p-0">
    <div class="bg-gray-300 rounded-t p-2">
        <p title="#{{$line->id}}">{{$line->name}}</p>
        <p title="Vietnamese"><i>{{$line->description}}</i></p>
    </div>
    <input type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[id][{{$rowIndex}}]" value="{{$line->id}}" >

    <div class="p-4">
        <div class="flex justify-center mb-2">
            @switch($line->control_type_id)
                @case (1)  {{-- 1 => "text" --}}
                    <x-controls.insp_chklst.check-point-text :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug"/>
                @break
                @case (4)  {{-- 4 => "radio" --}}
                    <div class="w-full">
                        <x-controls.insp_chklst.check-point-option :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug"/>
                        <x-controls.insp_chklst.check-point-create-ncr-on-hold :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug"/>
                    </div>
                @break
                @case (7) {{-- 7 => "signature" --}}
                    <x-controls.insp_chklst.check-point-signature :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug"/>
                @break
                @default
                    Unknown control_type_id {{$line->control_type_id}} ({{$controlType[$line->control_type_id]}})
                @break
            @endswitch
        </div>
        {{-- <input  name="{{$table01Name}}[owner_id][{{$rowIndex}}]" 
                id="{{$table01Name}}[owner_id][{{$rowIndex}}]" 
                value="{{$line->owner_id}}" 
                onchange="console.log('{{$line->value}}', {{$line->owner_id}})"
                /> --}}
        <x-renderer.attachment2 name="table01[insp_photos][{{$rowIndex}}]" :value="$attachments"/>
        <br/>
        <x-controls.comment.comment-group2a category="insp_comments" commentableType="{{$type}}_line" commentableId="{{$line->id}}" />
    </div>
</x-renderer.card>