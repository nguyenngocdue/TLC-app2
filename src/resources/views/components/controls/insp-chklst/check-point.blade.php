<x-renderer.card class="my-1">
    <p title="{{$line->name}} - #{{$line->id}}">{{$line->description}}</p>
    <input type="{{$debug?"text":"hidden"}}" name="{{$table01Name}}[id][{{$rowIndex}}]" value="{{$line->id}}" >

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
    Comment here
</x-renderer.card>