@if($value =='' && $signatureId)            
    <div title="#{{$signatureId}}">Request sent on {{$sentDate}}</div>
    @if(!$inNominatedList)
        <x-renderer.button 
            id="btnRecall_{{$signatureUserId}}"
            type="warning"
            onClick="recallSignOff('{{$tableName}}', {{$signableId}}, [{{$signatureUserId}}], [{{$signatureId}}])"
            >
            Recall this request
        </x-renderer.button>
    @endif
@else
    @if($isExternalInspector)
        Request not yet sent
    @else
        <x-renderer.button 
            id="btnRequest_{{$signatureUserId}}" 
            type="secondary" 
            class="my-2" 
            disabled="{{$readOnly}}"
            onClick="requestSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$signatureUserId}}])">
            Request to Sign Off
        </x-renderer.button>
    @endif
@endif