<x-renderer.card class="border my-1 bg-white mt-2 p-0">
    <div class="bg-amber-300 rounded-t p-2">
        <p>{{$title}}</p>
    </div>
    {{-- <x-renderer.card title="Designated Approvers" class='border1 m-4'>
        <x-controls.has-data-source.dropdown2 type={{$type}} name='{{$approverFn}}()' :selected="$selected" multiple={{true}}  />
    </x-renderer.card> --}}
    @php $index = 0; @endphp
    @foreach($signatures as $signature)
    {{-- @dump($signature) --}}
    <div class="w-full bg-lime-100 flex justify-center my-2">
        <div class="text-right p-2 rounded w-1/2">
            {{$debug?"signatures[$index][id]":""}}
            <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][id]" value="{{$signature['id']}}">
            {{$debug?"signatures[$index][signable_id]":""}}
            <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][signable_id]" value="{{$signableId}}">
            <div class="w-[340px] h-36 mx-auto">
                <x-controls.signature2 
                    name="signatures[{{$category}}_{{$index}}][value]"
                    signableTypeColumnName="signatures[{{$category}}_{{$index}}][signable_type]"
                    signableType="{{$signableType}}"
                    categoryColumnName="signatures[{{$category}}_{{$index}}][category]"
                    category="{{$category}}"
                    value="{{$signature['value']}}"
                    signedPersonId="{{$signature['owner_id']}}"
                    updatable="{{$signature['updatable']}}"
                />
            </div>
            <div>
                <x-controls.insp-chklst.name-position :user="$signature['user']" subText="Signed at {{$signature['user']['timestamp']}}"/>                        
            </div>
        </div>
    </div>
    @php $index ++; @endphp
    @endforeach
    {{-- Awaiting for sign off --}}
    @foreach($remindList as $user)
    <div class="w-full bg-red-100 flex justify-center my-1">
        <div class="text-right p-2 rounded w-1/2">
            <div class="my-2">
                <x-controls.insp-chklst.name-position :user="$user['full']" subText="Has not signed yet"/>  
            </div>
        </div>
    </div>
    @endforeach
    @if(!empty($remindList))
        <div class="flex justify-center w-full">
            <x-renderer.button 
            title='{{$requestButtonTitle}}' 
            icon="fa-duotone fa-paper-plane" 
            class="w-3/4 h-full bg-lime-200 mb-2"
            onClick="sendRemindToPeople([{{join(',', array_keys( $remindList))}}], '{{$type}}', {{$signableId}})"
            >Request to sign off</x-renderer.button>
        </div>
    @endif 
    @if($isRequestedToSign0)
        @if(!$alreadySigned)
        <div class="w-full bg-blue-100 flex justify-center my-2">
            <div class="text-right p-2 rounded w-1/2">
                {{$debug?"signatures[$index][id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][id]" value="">
                {{$debug?"signatures[$index][signable_id]":""}}
                <input class="w-1/4" type="{{$input_or_hidden}}" name="signatures[{{$category}}_{{$index}}][signable_id]" value="{{$signableId}}">
                <div class="w-[340px] h-36 mx-auto">
                    <x-controls.signature2 
                        name="signatures[{{$category}}_{{$index}}][value]"
                        signableTypeColumnName="signatures[{{$category}}_{{$index}}][signable_type]"
                        signableType="{{$signableType}}"
                        categoryColumnName="signatures[{{$category}}_{{$index}}][category]"
                        category="{{$category}}"
                        ownerIdColumnName="signatures[{{$category}}_{{$index}}][owner_id]"
                        value=""
                        signedPersonId=""
                    />
                </div>
                <div>
                    <x-controls.insp-chklst.name-position :user="$currentUser" subText="Current timestamp will be applied"/>                        
            </div>
        </div>
        @else
            {{-- <x-feedback.alert type="success" titleless=1 message="You have signed off this document." /> --}}
        @endif
    @else 
        {{-- <x-feedback.alert type="info" titleless=1 message="You are not requested to sign off this document." /> --}}
    @endif
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
        toastr.warning("There is no desinated approver, nothing sent. Maybe you forgot to save the form after inputing some names.")
    }
}
</script>