@if($value =='' && $signatureId)            
    <div title="#{{$signatureId}}">Request sent on {{$sentDate}}</div>
    {{-- @dump($readOnly, $isInNominatedList) --}}
    {{-- @if(!$isExternalInspector && !$isProjectClient) --}}
        @if(!$readOnly)
            @if($isSignOffAdmin)
                <x-renderer.button 
                    id="btnRecall_{{$signatureUserId}}"
                    type="warning"
                    onClick="recallSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$signatureUserId}}], [{{$signatureId}}])"
                    >
                    Recall this request
                </x-renderer.button>
            @endif
        @endif
    {{-- @endif --}}
@else
    @if($isSignOffAdmin)
        <x-renderer.button 
            id="btnRequest_{{$signatureUserId}}" 
            type="secondary" 
            class="my-2" 
            disabled="{{$readOnly}}"
            onClick="requestSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$signatureUserId}}])">
            Request to Sign Off
        </x-renderer.button>
    @else
        Request not yet sent
    @endif
@endif