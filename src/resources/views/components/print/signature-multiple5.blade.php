@foreach($dataSource as $key => $signature)
@php
            $name = 'signature'. ($signature->id ?? $signature['id']);
            $value = $signature->value ?? $signature['value'];
            $userId = $signature->user_id ?? $signature['user_id'];
            $updateAt = $signature->update_at ?? $signature['updated_at'];
            $dateTime = \App\Utils\Support\DateTimeConcern::convertForLoading('picker_datetime', $updateAt) ?? "";
            // $commentValue = $signature->signature_comment ?? null;
            @endphp
        
        <div class='border -mx-1 my-1 py-1'>
            <div class="flex justify-center">
                <x-controls.signature.signature2a name={{$name}} value={{$value}} signatureId={{$userId}} readOnly=1 showCommentBox={{true}} commentName="comment_{{$userId}}" />                            
            </div>
            @if(isset($signature->signature_comment))
            <div class='border rounded bg-gray-100 p-1 m-1'>
                {{$signature->signature_comment}}
            </div>
            @endif
            <div class='mt-2 flex justify-center'>
                <div>
                    <x-renderer.avatar-user size="xl" uid="{{$userId}}" showCompany=1 content={{$dateTime}}/>
                </div>
            </div>
        </div>
   @endforeach