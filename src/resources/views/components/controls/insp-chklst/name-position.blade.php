<div class="flex justify-end text-sm mb-2">
    <div class="text-right mr-2">
        <span class="font-semibold" title="#{{$user['id']}}">{{$user['full_name']}}</span>
        <br/>
        ({{$user['position_rendered']}})
        <br/>
        <i>Signed at {{$user['timestamp']}}</i>
    </div>
    <div>
        <x-renderer.image class="rounded-full" w=56 h=56 src="{{$user['avatar']}}" />
    </div>
</div>