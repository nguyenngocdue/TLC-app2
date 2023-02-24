@php
    $classIsRead = $isRead ? '' : 'bg-gray-50';
@endphp
<a href="{{route('notifications.markAsRead',['type' => $type,'id' => $id,'idNotification' => $idNotification])}}" 
    aria-current="true" class="block {{$classIsRead}} w-full px-4 py-2 border rounded border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    <div class="flex items-center">
        <div class="w-full grid grid-cols-6 gap-x-1">
            <div class="col-span-2">
                <span class="flex">{{$documentType}}</span>
                <span class="ml-2 text-xs font-light text-red-400">{{$timeAgo}}</span>
            </div>
            <span class="col-span-3 flex items-center truncate">{{$title}}</span>
            <div class="ml-2 flex items-center">
                <div class="h-2 w-2 rounded-full bg-green-500"></div>
                <span class="ml-2">{{$status}}</span>
            </div>
        </div>
        @if(!$isRead)
            <div class="justify-end h-1.5 w-1.5 rounded-full bg-blue-500"></div>
        @endif
    </div>
</a>
