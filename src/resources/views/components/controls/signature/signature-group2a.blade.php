<div class="border px-4 py-2">
{{-- @dump($inNominatedList) --}}
{{-- @dump($needToRecall) --}}
    <div component="signature-group2a-loading" class="signature-group2a-loading">Loading wsClient...</div>
    <div component="signature-group2a" class="hidden signature-group2a">
        <x-controls.signature.signature-group2a-need-to-recall 
            inNominatedList="{{$inNominatedList}}"
            tableName="{{$tableName}}"
            signableId="{{$signableId}}"

            :needToRecall="$needToRecall"
            :needToRecallSignatures="$needToRecallSignatures"
            />

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
                        
                        showCommentBox=1                    
                        commentName="signatures[{{$category}}_{{$index}}][signature_comment]"
                        commentValue="{{$comment}}"

                        showDecisionBox=1
                        decisionName="signatures[{{$category}}_{{$index}}][signature_decision]"
                        decisionValue="{{$decision}}"
                        />
                    </div>
                @else
                    <x-controls.signature.signature-group2a-request-recall-one
                        value="{{$value}}"
                        signatureId="{{$signatureId}}"
                        isExternalInspector="{{$isExternalInspector}}"
                        signatureUserId="{{$signatureUserId}}"
                        readOnly="{{$readOnly}}"
                        tableName="{{$tableName}}"
                        signableId="{{$signableId}}"
                        category="{{$category}}"
                        sentDate="{{$sentDate}}"
                        inNominatedList="{{$inNominatedList}}"
                    />
                @endif
            </div>
            <x-renderer.avatar-user size="xlarge" uid="{{$user->id}}" flipped=1 content=""/>
        </div>
        @endforeach
        
        <x-controls.signature.signature-group2a-request-recall-many
            :needToRequest="$needToRequest"
            :alreadyRequested="$alreadyRequested"
            :nominatedList="$nominatedList"
            :alreadyRequestedSignatures="$alreadyRequestedSignatures"
            tableName="{{$tableName}}"
            signableId="{{$signableId}}"
            category="{{$category}}"
            inNominatedList="{{$inNominatedList}}"
            
        />
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