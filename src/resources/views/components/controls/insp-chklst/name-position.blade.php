@props(['subText' => 'subTextHere', 'user'])
<div class="flex justify-end text-sm mb-2">
    <div class="text-right mr-2">
        <span class="font-semibold" title="#{{$user['id']}}">{{$user['full_name']}}</span>
        <br/>
        ({{$user['position_rendered']}})
        <br/>
        <i>{{$subText}}</i>
    </div>
    <div>
        <x-renderer.image spanClass="w-14 h-14" class="rounded-full" src="{{$user['avatar']}}" />
    </div>
</div>