<div class="flex justify-end text-sm mb-2">
    <div class="text-right mr-2">
        <span class="font-semibold" title="#{{$user['id']}}">{{$user['full_name']}}</span>
        <br/>
        ({{$user['position_rendered']}})
        <br/>
        @if($user['timestamp'])
            <i>Signed at {{$user['timestamp']}}</i>
        @else
            <i>Current timestamp will be applied</i>
        @endif
    </div>
    <div>
        <x-renderer.image spanClass="w-14 h-14" class="rounded-full" src="{{$user['avatar']}}" />
    </div>
</div>