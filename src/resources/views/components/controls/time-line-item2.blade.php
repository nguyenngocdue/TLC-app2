
@switch($key)
@case('create')
    <div class="border p-1 mt-2 flex justify-center">
        <x-renderer.avatar-user>{!!$user!!}</x-renderer.avatar-user>
        <div class="w-full">
            <div class="text-sm italic text-center">Created</div>
            <div title="{{$timeFull}}" class="text-xs italic md:mb-3 text-center">({{$timeAgo}})</div>
        </div>
    </div>
    @break
@case('entity_status')
    <div class="flex items-center relative">
        <div class="ml-20 hidden md:block w-64">
            <x-renderer.avatar-user timeLine="true">{!!$user!!}</x-renderer.avatar-user>
        </div>
        <div class="border-r-2 border-gray-200 absolute h-full left-60 top-2 z-10">
            <i class="fa-sharp fa-solid fa-circle-dot -top-1 -left-[7px] absolute text-blue-600"></i>
        </div>
        <div class="ml-24 w-full">
                <div title="{{$timeFull}}" class="text-sm italic md:mb-3 text-left">({{$timeAgo}})</div>
                <div class="flex items-center p-1 border border-gray-300 rounded bg-gray-100 mb-4 w-max">
                    <p class="w-full flex">
                        <x-renderer.status>{{$statusOld}}</x-renderer.status>
                        <i>=></i>
                        <x-renderer.status>{{$statusNew}}</x-renderer.status>
                    </p>
                </div>       
        </div>
    </div>
    @break
    @case('comment')
    <div class="flex items-center relative">
        <div class="ml-20 hidden md:block w-64">
            <x-renderer.avatar-user timeLine="true">{!!$userComment!!}</x-renderer.avatar-user>
        </div>
        <div class="border-r-2 border-gray-200 absolute h-full left-60 top-2 z-10">
            <i class="fa-sharp fa-solid fa-circle-dot -top-1 -left-[7px] absolute text-blue-600"></i>
        </div>
        <div class="ml-24 w-full">
                <div title="{{$timeFull}}" class="text-sm italic md:mb-3 text-left">({{$timeAgo}})</div>
                <div class="text-sm md:mb-3 font-medium whitespace-nowrap text-left">{{$nameComment}}</div>
                <div class="flex items-center p-1 border border-gray-300 rounded bg-gray-100 mb-4 w-max px-2" title="{{$contentTitle}}">
                    <p class="w-max flex font-normal text-sm">
                        {{$contentComment}}
                    </p>
                </div>       
        </div>
    </div>
    @break
@default
@endswitch