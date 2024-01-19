@php
    $title_email_to = $needToRequest->join(", ");
    $title_already_signed = $notYetSigned->join(", ");
@endphp
@if(count($nominatedList) > 0)            
    <div class="text-right">
        @if(count($needToRequest) > 0)
            <x-renderer.button 
                id="btnRequest_NeedToRequest" 
                type="secondary" 
                onClick="requestSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$needToRequest->map(fn($i, $id)=>$id)->join(',')}}])"
                title='{!! "Email to:\n".$title_email_to !!}'
            >Send Request to {{count($needToRequest)}} Participant(s).</x-renderer.button>
        @endif
        
        @if(!$isInNominatedList && count($notYetSigned) > 0)
        <x-renderer.button 
            id="btnRecall_NeedToRecall" 
            type="warning"
            title='{!! "Recall:\n".$title_already_signed !!}'
            onClick="recallSignOff('{{$tableName}}', {{$signableId}}, [{{$notYetSigned->map(fn($i, $id) => $id)->join(',')}}], [{{$notYetSignedSignatures->join(',')}}])"
        >Recall {{count($notYetSigned)}} Request(s).</x-renderer.button>
        @endif
    </div>
@else
    <div class="text-center text-gray-400">
        There is no nominated people.
    </div>
@endif