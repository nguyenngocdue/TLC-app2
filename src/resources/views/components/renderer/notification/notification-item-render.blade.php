@php
    $classIsRead = $isRead ? 'bg-gray-100' : "";
    $href = route('notifications.markAsRead',['type' => $objectType,'id' => $objectId,'idNotification' => $id]);
@endphp
<a href="{{$href}}" aria-current="true" class="{{$classIsRead}} block shadow w-full px-2 py-2 mb-1 border rounded border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    <div class="flex justify-between">
        <div class="flex items-center">
            <div class="w-50">
                <x-renderer.avatar-user uid='{{$senderId}}' title='' description=''></x-renderer.avatar-user>
            </div>
            <div class="{{$isRead?"text-gray-400":"text-black"}}">
                <p class="">{!! $message!!}</p>
                <p class="text-sm {{$isRead ? "text-blue-400": "text-blue-600"}}">{{$timeAgo}}</p>
            </div>
        </div>
        <div class="mx-3 my-auto ">
            <div class="h-3 w-3 {{$isRead ?: "rounded-full bg-blue-500"}}"></div>
        </div>
    </div>
</a>