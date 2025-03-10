<div component="check-point-create-ncr-on-hold">
    @php
        $behaviorId = $line->getControlValue?->behavior_of;
    @endphp
    @if($relatedEntities && count($relatedEntities))
        <div>
            Related {{$syntax}}(s):
            @foreach($relatedEntities as $item)
            <li>
                @php
                    $owner = $item->getOwner;
                    $ownerName = 'Creator: ' . $owner->name0 . " ";
                    $workAreaName = $item->getWorkArea->name ?? '';
                    $information = $ownerName . ($workAreaName ? ' (' . $workAreaName .')' : '');
                @endphp
                <x-renderer.button target="_blank" class="m-1" title="{!! $item->description !!} (#{{$item->id}})" href="{{route($nameShow, $item->id)}}">
                    {{$item->name}}. {{$information}}. <x-renderer.status>{{$item->status}}</x-renderer.status>
                </x-renderer.button>
            </li>
            @endforeach
        </div>
    @endif
    
    @if(!$isExternal)
        <div id="divSubOptionNCR_{{$line->id}}" class="{{$behaviorId == config('insp_chklst.fail') ? "" : "hidden"}}">
            <x-renderer.button disabled="{{$readOnly}}" target="_blank" type='success' href="{!! $href !!}" class="m-1">{{$nameButton}}</x-renderer.button>
        </div>
        
        <div id="divSubOptionOnHold_{{$line->id}}" class="{{$behaviorId == config('insp_chklst.on_hold') ? "" : "hidden"}}">
            On Hold comment:<textarea class="border rounded w-full border-gray-300 {{$readOnly?"bg-gray-100":""}}" @readonly($readOnly) name="{{$table01Name}}[value_on_hold][{{$rowIndex}}]">{{$line->value_on_hold}}</textarea>
        </div>
    @endif
</div>
