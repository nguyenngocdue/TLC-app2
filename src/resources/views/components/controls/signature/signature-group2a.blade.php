
@foreach($signatures as $index => $user)
@php
    $as = $user->attached_signature;   
    $value = $as ? $as->value : "";
    $comment = $as ? $as->signature_comment : "";
    $signatureId = $as ? $as->id : "";

    $mineSignature = $cuid == $user->id;
    $signatureUserId = $user->id;
    $bgColor = "bg-blue-200";
    if($value != '') $bgColor = "bg-lime-200";
    if($value =='' && $signatureId) $bgColor = "bg-pink-200";
@endphp
<div class="{{$bgColor}} my-2 py-2 rounded ">
        <div class="w-full bg-pi1nk-400 text-center">
            @if($as)
                <input type="{{$input_or_hidden}}" class="rounded" name="signatures[{{$category}}_{{$index}}][id]" value="{{$signatureId}}" />
                <div class="flex w-full bg-gre1en-300 justify-center">
                <x-controls.signature.signature2a                    
                    name="signatures[{{$category}}_{{$index}}][value]"
                    value="{{$value}}"
                    debug="{{$debug ? 1 : 0}}"
                    readOnly="{{$mineSignature ? 0 : 1}}"
                    {{-- signatureUserId="{{$signatureUserId}}" --}}
                    
                    showCommentBox=1
                    comment="{{$comment}}"
                    />
                </div>
            @else
                <x-renderer.button type="primary" class="my-2">Request to Sign Off</x-renderer.button>
            @endif
        </div>
        <x-renderer.avatar-user size="xlarge" uid="{{$user->id}}" flipped=1 content=""/>
    </div>
@endforeach