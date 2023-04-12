<x-renderer.card class="my-1">
    <p title="{{$line->name}} - #{{$line->id}}">{{$line->description}}</p>
    <div class="flex justify-center">
        @switch($line->control_type_id)
            @case (4)  {{-- 4 => "radio" --}}
                <x-controls.check-point-option :line="$line" />
            @break
            @case (7) {{-- 7 => "signature" --}}
                <x-controls.check-point-signature :line="$line" />
            @break
            @default
                Unknown control_type_id {{$line->control_type_id}} ({{$controlType[$line->control_type_id]}})
            @break
        @endswitch
    </div>
    Attachment here
    <br/>
    Comment here
</x-renderer.card>