@if(!$inNominatedList && count($needToRecall)>0)
<div class="bg-red-600 text-white  rounded p-2">
    @php $title_need_to_recall = $needToRecall->join(", "); @endphp
    <div class="font-bold flex items-center gap-2" title="You have sent request but you have removed them from norminated list.">
        Need to recall: 
        <x-renderer.button 
            type="warning"
            title='{!! "Recall:\n".$title_need_to_recall !!}'
            onClick="this.disabled=true;recallSignOff('{{$tableName}}', {{$signableId}}, [{{$needToRecall->map(fn($i, $uid)=>$uid)->join(',')}}], [{{$needToRecallSignatures->join(',')}}])"
        >Recall {{count($needToRecall)}} Request(s).</x-renderer.button>
    </div>
    <div class="flex">
        @foreach($needToRecall as $uid => $a)
            @php
                $u = App\Models\User::find($uid);                    
            @endphp
            <div class="flex items-center gap-2 p-1"> 
                <img class="w-10 h-10 rounded-full" src="{{$u->getAvatarThumbnailUrl()}}" />{{$u->name}}
            </div>
        @endforeach
    </div>
</div>
@endif