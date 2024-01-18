@php
    $title_email_to = $needToRequest->join(", ");
    $title_already_signed = $alreadyRequested->join(", ");
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
            @if(!$inNominatedList && count($alreadyRequested) > 0)
            <x-renderer.button 
                id="btnRecall_NeedToRecall" 
                type="warning"
                title='{!! "Recall:\n".$title_already_signed !!}'
                onClick="recallSignOff('{{$tableName}}', {{$signableId}}, [{{$alreadyRequested->map(fn($i, $id) => $id)->join(',')}}], [{{$alreadyRequestedSignatures->join(',')}}])"
            >Recall {{count($alreadyRequested)}} Request(s).</x-renderer.button>
            @endif
        @else 
        {{-- <x-renderer.button type="secondary" disabled>
            All participants are Requested.
        </x-renderer.button> --}}
            @if(!$inNominatedList)
                <x-renderer.button 
                    id="btnRecallAllRequest"
                    type="warning"
                    title='{!! "Recall:\n".$title_already_signed !!}'
                    onClick="this.disabled=true; recallSignOff('{{$tableName}}', {{$signableId}}, [{{$alreadyRequested->map(fn($i, $id) => $id)->join(',')}}], [{{$alreadyRequestedSignatures->join(',')}}])"
                    >
                Recall {{count($alreadyRequested)}} Request(s).</x-renderer.button>
            @endif
        @endif
    </div>
@else
    <div class="text-center text-gray-400">
        There is no nominated people.
    </div>
@endif