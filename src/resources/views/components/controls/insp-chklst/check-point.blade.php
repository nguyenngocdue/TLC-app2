<x-renderer.card class="my-1" px="0" py="0">
    <div class="bg-gray-300 rounded-t p-2">
        <p title="{{$line->name}} - #{{$line->id}}">{{$line->description}}</p>
    </div>
    <input type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[id][{{$rowIndex}}]" value="{{$line->id}}" >

    <div class="p-4">
        <div class="flex justify-center">
            @switch($line->control_type_id)
                @case (4)  {{-- 4 => "radio" --}}
                    <x-controls.insp_chklst.check-point-option :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug"/>
                @break
                @case (7) {{-- 7 => "signature" --}}
                    <x-controls.insp_chklst.check-point-signature :line="$line" :table01Name="$table01Name" :rowIndex="$rowIndex" :debug="$debug"/>
                @break
                @default
                    Unknown control_type_id {{$line->control_type_id}} ({{$controlType[$line->control_type_id]}})
                @break
            @endswitch
        </div>
        <x-renderer.attachment2 name="table01[insp_photos][{{$rowIndex}}]" :value="$attachments"/>
        <br/>
        <x-controls.comment-group2 name="insp_comments" type="qaqc_insp_chklst_line" :rowIndex="$rowIndex" />
    </div>
</x-renderer.card>