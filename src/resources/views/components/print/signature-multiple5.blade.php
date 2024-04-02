<div class="flex justify-center">
    <div>
    @foreach($dataSource as $key => $signature)
        @php
            $name = 'signature'. ($signature->id ?? $signature['id']);
            $value = $signature->value ?? $signature['value'];
            $userId = $signature->user_id ?? $signature['user_id'];
            $updateAt = $signature->update_at ?? $signature['updated_at'];
            $dateTime = \App\Utils\Support\DateTimeConcern::convertForLoading('picker_datetime', $updateAt) ?? "";
        @endphp
        <div class="flex justify-between">
        <x-controls.signature.signature2a name={{$name}} value={{$value}} signatureId={{$userId}} readOnly=1 />                            
        <x-renderer.avatar-user size="xlarge" uid="{{$userId}}" showCompany=1 content={{$dateTime}}/>
        </div>
       
   @endforeach
    </div>
   
</div>