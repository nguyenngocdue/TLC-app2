<x-renderer.card class="border my-1 bg-white mt-2 p-0">
    <div class="bg-amber-300 rounded-t p-2">
        <p>{{$title}}</p>
    </div>
    {{-- <x-renderer.card title="Designated Approvers" class='border1 m-4'>
        <x-controls.has-data-source.dropdown2 type={{$type}} name='{{$approverFn}}()' :selected="$selected" multiple={{true}}  />
    </x-renderer.card> --}}
    @php $index = 0; @endphp
    @if(sizeof($designatedApprovers) == 0) 
    <div class="text-sm text-center p-2">Not found nominated approvers for this step. Please fill them in <b>{{$labelOfApproverFn}}</b>.</div>
    @endif
    @foreach($signatures as $signature)
    {{-- @dump($signature) --}}
    <div class="w-full bg-lime-100 flex justify-center my-2">
        <div class="text-right p-2 rounded w-1/2">
            {{$debug?"signatures[$index][id]":""}}
            <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][id]" value="{{$signature['id']}}">
            {{$debug?"signatures[$index][signable_id]":""}}
            <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][signable_id]" value="{{$signableId}}">
            <div class="w-[340px] mx-auto">
                <x-controls.signature2 
                    name="signatures[{{$category}}_{{$index}}][value]"
                    signableTypeColumnName="signatures[{{$category}}_{{$index}}][signable_type]"
                    signableType="{{$signableType}}"
                    categoryColumnName="signatures[{{$category}}_{{$index}}][category]"
                    category="{{$category}}"
                    value="{{$signature['value']}}"
                    signatureComment="{{$signature['signature_comment']}}"
                    signatureCommentColumnName="signatures[{{$category}}_{{$index}}][signature_comment]"
                    signedPersonId="{{$signature['owner_id']}}"
                    updatable="{{$signature['updatable']}}"
                />
                <x-renderer.avatar-user size="xlarge" uid="{{$signature['owner_id']}}" flipped=1 content="Signed at {{$signature['user']['timestamp']}}"></x-renderer.avatar-user> 
            </div>
            <div>
            </div>
        </div>
    </div>
    @php $index ++; @endphp
    @endforeach
    {{-- Awaiting for sign off --}}
    @foreach($remainingList as $user)
    <div class="w-full {{$user['valid_email'] ? 'bg-red-100' : 'bg-rose-300'}} flex justify-center my-1">
        <div class="text-right p-2 rounded w-1/2">
            <div class="w-[340px] mx-auto my-2">
                <x-renderer.avatar-user size="xlarge" uid="{{$user['full']->id}}" flipped=1 content="{{$user['valid_email'] ? 'Has not signed yet' : 'Email address is invalid'}}"/>
            </div>
        </div>
    </div>
    @endforeach
    @if(!empty($remainingList))
        <div class="flex justify-center w-full">
            <x-renderer.button 
            title='{{$requestButtonTitle}}' 
            icon="fa-duotone fa-paper-plane" 
            class="w-3/4 h-full bg-lime-200 mb-2"
            onClick="sendRemindToPeople([{{join(',', $remindList)}}], '{{$type}}', {{$signableId}})"
            >Request to sign off</x-renderer.button>
        </div>
    @endif 
    @if($isRequestedToSign0 && !$alreadySigned)
        <div class="w-full bg-blue-100 flex justify-center my-2">
            <div class="text-right p-2 rounded w-1/2">
                {{$debug?"signatures[$index][id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][id]" value="">
                {{$debug?"signatures[$index][signable_id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][signable_id]" value="{{$signableId}}">
                <div class="w-[340px] mx-auto">
                    <x-controls.signature2 
                        name="signatures[{{$category}}_{{$index}}][value]"
                        signableTypeColumnName="signatures[{{$category}}_{{$index}}][signable_type]"
                        signableType="{{$signableType}}"
                        categoryColumnName="signatures[{{$category}}_{{$index}}][category]"
                        category="{{$category}}"
                        ownerIdColumnName="signatures[{{$category}}_{{$index}}][owner_id]"
                        value=""
                        signatureComment="Reviewed and confirmed."
                        signatureCommentColumnName="signatures[{{$category}}_{{$index}}][signature_comment]"
                        signedPersonId=""
                    />
                    <x-renderer.avatar-user size="xlarge" uid="{{$currentUser['id']}}" flipped=1 content="Current timestamp will be applied"/>
                </div>
                <div>
                </div>
            </div>
        </div>
    @endif
</x-renderer.card>

@once
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
        toastr.warning("There is no desinated approver, nothing sent. Maybe you forgot to save the form after inputing some names.")
    }
}
</script>
@endonce