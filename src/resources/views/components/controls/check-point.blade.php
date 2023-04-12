<x-renderer.card>
    <p title="{{$line->name}}">{{$line->description}}</p>
    @switch($line->control_type_id)
        @case (4)  {{-- 4 => "radio" --}}
            <x-controls.check-point-option lineId="{{$line->id}}" />
        @break
        @case (7) {{-- 7 => "signature" --}}
            Show signature
        @break
        @default
            Unknown control_type_id {{$line->control_type_id}} ({{$controlType[$line->control_type_id]}})
        @break
    @endswitch
</x-renderer.card>