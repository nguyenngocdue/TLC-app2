<div component="check-point-create-ncr-on-hold">
    @if($relatedNcrs && count($relatedNcrs))
        <div id="divRelatedNCRs"> 
            Related NCR(s):
            @foreach($relatedNcrs as $ncr)
                <x-renderer.button class="m-1" title="{!! $ncr->description !!} (#{{$ncr->id}})" href="{{route('qaqc_ncrs.show', $ncr->id)}}">
                    {{$ncr->name}} ({{$ncr->status}})
                </x-renderer.button>
            @endforeach   
        </div>
    @endif
    <div id="divSubOptionNCR_{{$line->id}}" class="hidden">
        <x-renderer.button type='success' href="{!! $href !!}" class="m-1">Create a new NCR</x-renderer.button>
    </div>
    <div id="divSubOptionOnHold_{{$line->id}}" class="hidden">
        On Hold comment:<textarea class="border rounded w-full border-gray-300" name="{{$table01Name}}[value_on_hold][{{$rowIndex}}]">{{$line->value_on_hold}}</textarea>
    </div>
</div>
<script>
    initClick({{$line->id}}, {{$line->qaqc_insp_control_value_id}})
</script>