<div component="check-point-create-ncr-on-hold">
    @if($relatedEntities && count($relatedEntities))
        <div id="divRelatedNCRs"> 
            Related {{$syntax}}(s):
            @foreach($relatedEntities as $value)
                <x-renderer.button target="_blank" class="m-1" title="{!! $value->description !!} (#{{$value->id}})" href="{{route($nameShow, $value->id)}}">
                    {{$value->name}} (<x-renderer.status>{{$value->status}}</x-renderer.status>)
                </x-renderer.button>
            @endforeach   
        </div>
    @endif
    <div id="divSubOptionNCR_{{$line->id}}" @class(['hidden' => !in_array($line->hse_insp_control_value_id,[2,6])])>
        <x-renderer.button target="_blank" type='success' href="{!! $href !!}" class="m-1">{{$nameButton}}</x-renderer.button>
    </div>
    <div id="divSubOptionOnHold_{{$line->id}}" class="hidden">
        On Hold comment:<textarea class="border rounded w-full border-gray-300" name="{{$table01Name}}[value_on_hold][{{$rowIndex}}]">{{$line->value_on_hold}}</textarea>
    </div>
</div>
<script>
    initClick({{$line->id}}, {{$line->qaqc_insp_control_value_id}})
</script>