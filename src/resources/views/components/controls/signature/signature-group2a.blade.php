
<div class="border px-4 py-2">
{{-- @dump($needToRecall) --}}
    <div component="signature-group2a-loading" class="signature-group2a-loading">Loading wsClient...</div>
    <div component="signature-group2a" class="hidden signature-group2a">
        @if(count($needToRecall)>0)
        <div class="bg-red-600 text-white font-bold rounded p-2">
            @foreach( $needToRecall as $request)
                @php
                    $title_need_to_recall = $needToRecall->join(", ");                
                @endphp
                Need to recall: 
                <x-renderer.button 
                type="warning"
                title='{!! "Recall:\n".$title_need_to_recall !!}'
                >Recall {{count($needToRecall)}} Request(s).</x-renderer.button>
            @endforeach
        </div>
        @endif

        @foreach($signatures as $index => $user)
        @php
            $as = $user->attached_signature;

            $value = $as ? $as->value : "";
            $comment = $as ? $as->signature_comment : "";
            $decision = $as ? $as->signature_decision : "";
            $signatureId = $as ? $as->id : "";
            $sentDate = $as ? $as->created_at->format('d/m/Y') : "";

            $otherSignature = $cuid != $user->id;
            $signatureUserId = $user->id;
            $bgColor = "bg-blue-200";
            if($value != '') $bgColor = "bg-lime-200";
            // dump($signatureId, $cuid);
            if($value =='' && $signatureId) $bgColor = ($signatureUserId == $cuid) ? "bg-pink-200" : "bg-blue-200";
            
        @endphp
        <div class="{{$bgColor}} my-2 py-2 rounded ">
            <div class="w-full bg-pi1nk-400 text-center">
                @if($as && ($value != '' || ($signatureUserId == $cuid)))
                    <input type="{{$input_or_hidden}}" class="rounded" name="signatures[{{$category}}_{{$index}}][id]" value="{{$signatureId}}" />
                    <div class="flex w-full bg-gre1en-300 justify-center">
                    <x-controls.signature.signature2a
                        name="signatures[{{$category}}_{{$index}}][value]"
                        value="{{$value}}"
                        debug="{{$debug ? 1 : 0}}"
                        readOnly="{{($readOnly || $otherSignature) ? 1 : 0}}"
                        title="#{{$signatureId}}"
                        {{-- signatureUserId="{{$signatureUserId}}" --}}
                        
                        showCommentBox=1                    
                        commentName="signatures[{{$category}}_{{$index}}][signature_comment]"
                        commentValue="{{$comment}}"

                        showDecisionBox=1
                        decisionName="signatures[{{$category}}_{{$index}}][signature_decision]"
                        decisionValue="{{$decision}}"
                        />
                    </div>
                @else
                @if($value =='' && $signatureId)            
                    <div title="#{{$signatureId}}">Request sent on {{$sentDate}}</div>
                    <x-renderer.button 
                        id="btnRecall_{{$signatureUserId}}"
                        type="warning"
                        onClick="recallSignOff('{{$tableName}}', {{$signableId}}, [{{$signatureUserId}}], [{{$signatureId}}])"
                        >
                        Recall this request
                    </x-renderer.button>
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
                @endif
            </div>
            <x-renderer.avatar-user size="xlarge" uid="{{$user->id}}" flipped=1 content=""/>
        </div>
        @endforeach
        
        @php
            $title_email_to = $needToRequest->join(", ");
            $title_already_signed = $alreadyRequested->join(", ");
        @endphp
        <div class="text-right">
            @if(count($nominatedList) > 0)            
                @if(count($needToRequest) > 0)
                    <x-renderer.button 
                        id="btnRequest_NeedToRequest" 
                        type="secondary" 
                        onClick="requestSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', [{{$needToRequest->map(fn($i, $id)=>$id)->join(',')}}])"
                        title='{!! "Email to:\n".$title_email_to !!}'
                    >Send Request to {{count($needToRequest)}} Participant(s).</x-renderer.button>
                    @if(count($alreadyRequested) > 0)
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
                <x-renderer.button 
                    id="btnRecallAllRequest"
                    type="warning"
                    title='{!! "Recall:\n".$title_already_signed !!}'
                    onClick="this.disabled=true; recallSignOff('{{$tableName}}', {{$signableId}}, [{{$alreadyRequested->map(fn($i, $id) => $id)->join(',')}}], [{{$alreadyRequestedSignatures->join(',')}}])"
                    >
                Recall {{count($alreadyRequested)}} Request(s).</x-renderer.button>
                @endif
                @else
                Please select some people in the List above.
            @endif
        </div>
    </div>
</div>

@once
<script>
const requestSignOff = (tableName, signableId, category, requestedArray) => {
    console.log(tableName, signableId, requestedArray)
    requestedArray.forEach(person2request =>$("#btnRequest_" + person2request).prop('disabled', true))
    $("#btnRequest_NeedToRequest").prop('disabled', true)

    const data = {
        tableName, 
        signableId, 
        uids: requestedArray,
        category,
        wsClientId,
    }
// console.log(data)
    $.ajax({
        method:'POST',
        url: '/api/v1/qaqc/request_to_sign_off',
        data,
        success: (response) => {
            // toastr.success(response.message)
            requestedArray.forEach(person2request => $("#btnRequest_" + person2request).replaceWith("Request Sent just now."))

        },
        error: (response)=>{
            // console.log(response)
            toastr.error(response.responseJSON.message, "Send emails failed.")
            requestedArray.forEach(person2request =>$("#btnRequest_" + person2request).prop('disabled', false))            
            $("#btnRequest_NeedToRequest").prop('disabled', false)
        }
    })
}

const recallSignOff = (tableName, signableId, requestedArray, signatureIds) => {
    // console.log( requestedArray, signatureIds)
    requestedArray.forEach(person2request =>$("#btnRecall_" + person2request).prop('disabled', true))
    $("#btnRecall_NeedToRecall").prop('disabled', true)

    const data = {
        tableName, 
        signableId, 
        signatureIds,
        wsClientId,
    }
// console.log(data)
    $.ajax({
        method:'POST',
        url: '/api/v1/qaqc/recall_to_sign_off',
        data,
        success: (response) => {
            // toastr.success(response.message)
            requestedArray.forEach(person2request => $("#btnRecall_" + person2request).replaceWith("Recall Sent just now."))

        },
        error: (response)=>{
            // console.log(response)
            toastr.error(response.responseJSON.message, "Send emails failed.")
            requestedArray.forEach(person2request =>$("#btnRecall_" + person2request).prop('disabled', false))            
            $("#btnRecall_NeedToRecall").prop('disabled', false)
        }
    })
}
</script>
@endonce

<script>
function show(){
    if(wsClientId) {
        $(".signature-group2a").toggle()
        $(".signature-group2a-loading").toggle()
    } else setTimeout(() => show(), 100);        
}show()
</script>