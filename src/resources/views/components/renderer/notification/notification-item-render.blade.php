@php
    $classIsRead = $isRead ? '' : 'bg-gray-50';
@endphp
<a href="{{route('notifications.markAsRead',['type' => $objectType,'id' => $objectId,'idNotification' => $id])}}" 
    aria-current="true" class="block {{$classIsRead}} w-full px-2 py-2 border rounded border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
    <div class="flex items-center">
        <div class="w-50">
            <x-renderer.avatar-user uid='{{$senderId}}' title='' description=''></x-renderer.avatar-user>
        </div>
        <div>
            {!! $message!!}
        </div>
        @if(!$isRead)
            <div class="ml-3 justify-end h-1.5 w-1.5 rounded-full bg-blue-500"></div>
        @else
            <div class="ml-3 justify-end h-1.5 w-1.5"></div>    
        @endif
    </div>
</a>