<div class="flex items-center justify-between">
    <span class="text-lg font-semibold"></span>
    @if($isShowAll)
        <a href="{{route('notifications.index')}}" class="mr-2 text-xs font-semibold text-blue-500 hover:text-blue-800">Show all</a>
    @endif
</div>
<div>
    <div class="flex items-center justify-between py-2">
        <span class="text-sm font-semibold">Assigned To Me</span>
        @php
            $resultAssigneeNotifications = '('.sizeof($assigneeNotifications).'/'.$totalAssigneeNotifications.')';
        @endphp
        <span class="text-xs font-semibold">{{$resultAssigneeNotifications}}</span>
    </div>
    <div class="w-full text-sm font-normal text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        @forelse($assigneeNotifications as $key => $value)
            <x-renderer.notification-item :dataSource="$value" />
        @empty
            <span class="block w-full px-4 py-2 border rounded border-gray-200 text-red-300">
                Empty Notifications
            </span>
        @endforelse
        
    </div>
</div>

<div>
    <div class="flex items-center justify-between py-2">
        <span class="text-sm font-semibold">Created By Me</span>
        @php
            $resultCreatedNotifications = '('.sizeof($createdNotifications).'/'.$totalCreatedNotifications.')';
        @endphp
        <span class="text-xs font-semibold">{{$resultCreatedNotifications}}</span>
    </div>
    <div class="w-full text-sm font-normal text-gray-900 bg-white   dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        @forelse($createdNotifications as $key => $value)
            <x-renderer.notification-item :dataSource="$value" />
        @empty
            <span class="block w-full px-4 py-2 border rounded border-gray-200 text-red-300">
                Empty Notifications
            </span>
        @endforelse
    </div>
</div>

<div>
    <div class="flex items-center justify-between py-2">
        <span class="text-sm font-semibold">Monitored By Me</span>
        @php
            $resultMonitorNotifications = '('.sizeof($monitorNotifications).'/'.$totalMonitorNotifications.')';
        @endphp
        <span class="text-xs font-semibold">{{$resultMonitorNotifications}}</span>
    </div>
    <div class="w-full text-sm font-normal text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        @forelse($monitorNotifications as $key => $value)
            <x-renderer.notification-item :dataSource="$value" />
        @empty
            <span class="block w-full px-4 py-2 border rounded border-gray-200 text-red-300">
                Empty Notifications
            </span>
        @endforelse
    </div>
</div>