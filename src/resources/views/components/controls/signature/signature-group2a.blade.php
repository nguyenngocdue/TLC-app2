
@foreach($signatures as $index => $user)
@php
    $as = $user->attached_signature;

    $value = $as ? $as->value : "";
    $comment = $as ? $as->signature_comment : "";
    $decision = $as ? $as->signature_decision : "";
    $signatureId = $as ? $as->id : "";
    $sentDate = $as ? $as->created_at->format('d/m/Y') : "";

    $mineSignature = $cuid == $user->id;
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
                    readOnly="{{$mineSignature ? 0 : 1}}"
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
                @else
                    @if($isExternalInspector)
                        Request not yet sent
                    @else
                        <x-renderer.button 
                            id="btnRequest_{{$signatureUserId}}" 
                            type="secondary" 
                            class="my-2" 
                            onClick="requestSignOff('{{$tableName}}', {{$signableId}}, '{{$category}}', {{$signatureUserId}})">
                            Request to Sign Off
                        </x-renderer.button>
                    @endif
                @endif
            @endif
        </div>
        <x-renderer.avatar-user size="xlarge" uid="{{$user->id}}" flipped=1 content=""/>
    </div>
@endforeach

@once
<script>
const requestSignOff = (tableName, signableId, category, person2request) => {
    console.log(tableName, signableId, person2request)
    $("#btnRequest_" + person2request).prop('disabled', true);

    $.ajax({
        method:'POST',
        url: '/api/v1/qaqc/request_to_sign_off',
        data:{
            tableName, 
            signableId, 
            uids: [person2request],
            category,
            wsClientId,
        },
        success: (response) => {
            // toastr.success(response.message)
            $("#btnRequest_" + person2request).replaceWith("Request Sent just now.")
        },
        error: (response)=>{
            // console.log(response)
            toastr.error(response.responseJSON.message, "Send emails failed.")
            $("#btnRequest_" + person2request).prop('disabled', false);
        }
    })
}
</script>
@endonce

