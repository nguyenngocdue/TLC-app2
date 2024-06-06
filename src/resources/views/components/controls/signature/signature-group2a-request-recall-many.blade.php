@php
    $title_email_to = $needToRequest->join(", ");
    $title_already_signed = $notYetSigned->join(", ");
@endphp

@if(count($nominatedList) > 0)            
    <div class="text-right">
        @if($isSignOffAdmin)
            @if(count($needToRequest) > 1)
                <x-renderer.button 
                    id="btnRequest_NeedToRequest_{{$category}}" 
                    type="secondary" 
                    onClick="requestSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$needToRequest->map(fn($i, $id)=>$id)->join(',')}}])"
                    title='{!! "Email to:\n".$title_email_to !!}'
                >Send Request to {{count($needToRequest)}} Participants.</x-renderer.button>
            @endif
            
            @if(count($notYetSigned) > 1)
            <x-renderer.button 
                id="btnRecall_NeedToRecall_{{$category}}" 
                type="warning"
                title='{!! "Recall:\n".$title_already_signed !!}'
                onClick="recallSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$notYetSigned->map(fn($i, $id) => $id)->join(',')}}], [{{$notYetSignedSignatures->join(',')}}])"
            >Recall {{count($notYetSigned)}} Requests.</x-renderer.button>
            @endif
        @endif
    </div>
@else
    <div class="text-center text-gray-400">
        There is no nominated people.
    </div>
@endif