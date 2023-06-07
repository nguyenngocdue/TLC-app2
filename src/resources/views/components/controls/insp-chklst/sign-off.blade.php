<x-renderer.card class="border my-1 bg-white mt-2 p-0">
    <div class="bg-amber-300 rounded-t p-2">
        <p>Third Party Sign Off</p>
    </div>
    <x-renderer.card title="Nominated Approvers" class='border1 mx-4'>
        <div class="grid grid-cols-12 gap-1">
            <div class="col-span-12 md:col-span-9">
                <x-controls.has-data-source.dropdown2 type={{$type}} name='getSignOff()' :selected="$selected" multiple={{true}}  />
            </div>
            <div class="col-span-12 md:col-span-3">
                <x-renderer.button 
                    title='{{$title}}' 
                    icon="fa-duotone fa-paper-plane" 
                    class="w-full h-full bg-lime-200"
                    onClick="sendRemindToPeople([{{join(',', array_keys( $remindList))}}], '{{$type}}', {{$signableId}})"
                    >Request to sign off</x-renderer.button>
            </div>
        </div>
        
    </x-renderer.card>
    <div class="flex justify-center">
        <div class="p-4">
            @php $index = 0; @endphp
            @foreach($signatures as $signature)
                {{-- @dump($signature) --}}
                {{$debug?"signatures[$index][id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][id]" value="{{$signature['id']}}">
                {{-- {{$debug?"signatures[$index][owner_id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][owner_id]" value="{{$signature['owner_id']}}"> --}}
                {{$debug?"signatures[$index][qaqc_insp_chklst_sht_id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][qaqc_insp_chklst_sht_id]" value="{{$signableId}}">
                <div class="text-right">
                    <div class="w-[340px] h-36">
                        <x-controls.signature2 
                            name="signatures[{{$index}}][value]"
                            value="{{$signature['value']}}"
                            signedPersonId="{{$signature['owner_id']}}"
                            updatable="{{$signature['updatable']}}"
                        />
                    </div>
                    <div>
                        <x-controls.insp-chklst.name-position :user="$signature['user']" />                        
                    </div>
                </div>
            @php $index ++; @endphp
            @endforeach
            @if($isRequestedToSign0)
                @if(!$alreadySigned)
                    <div class="text-right p-2 rounded bg-lime-50">
                        {{$debug?"signatures[$index][id]":""}}
                        <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][id]">
                        {{-- {{$debug?"signatures[$index][owner_id]":""}} --}}
                        {{-- <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][owner_id]" value="{{$currentUser['id']}}"> --}}
                        {{$debug?"signatures[$index][qaqc_insp_chklst_sht_id]":""}}
                        <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$index}}][qaqc_insp_chklst_sht_id]" value="{{$signableId}}">
                        <div class="w-[340px] h-36">
                            <x-controls.signature2 
                                name="signatures[{{$index}}][value]"
                                ownerIdColumnName="signatures[{{$index}}][owner_id]"
                                value=""
                                signedPersonId=""
                            />
                        </div>
                        <div>
                            <x-controls.insp-chklst.name-position :user="$currentUser" />                        
                    </div>
                @else
                    <x-feedback.alert type="success" titleless=1 message="You have signed off this document." />
                @endif
            @else 
                <x-feedback.alert type="info" titleless=1 message="You are not requested to sign off this document." />
            @endif
        </div>
    </div>
</x-renderer.card>

<script>
    function sendRemindToPeople(uids, signable_type, signable_id, cu) {
    const requester = @json($currentUser);
    // console.log("Send remind to",uids,"from requester", requester)
    const doc = {signable_type, signable_id, requester}
    if(uids.length>0){
        $.ajax({
            type: "POST",
            url:'/api/v1/qaqc/remind_sign_off',
            data: {uids, doc},
            success: (response)=>{
                // console.log(response)
                toastr.success(response.message)
            },
            error: (response)=>{
                // console.log(response)
                toastr.error(response.responseJSON.message, "Send emails failed.")
            }
        })
    } else {
        toastr.warning("There is no nominated approver, nothing sent. Maybe you forgot to save the form after inputing some names.")
    }
}
</script>