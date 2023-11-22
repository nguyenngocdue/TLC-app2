<div class="flex items-center justify-between">
    <span class="text-lg font-semibold"></span>
    @if($isShowAll)
        <a href="{{route('notifications.index')}}" class="mr-2 text-xs font-semibold text-blue-500 hover:text-blue-800">Show all</a>
    @endif
</div>
@forelse ($notifications as $key => $group)
<div>
    <div class="flex items-center justify-between py-2">
        <span class="text-sm font-semibold">{{$key}}</span>
        @php
            $group = $group->toArray();
            $noti = $group;
            $totalNoti = sizeof($group);
            if ($isShowAll) {
                $noti = array_slice($group, 0, 10, true);
            }
            $results = '('.sizeof($noti).'/'.$totalNoti.')';
        @endphp
        <span class="text-xs font-semibold">{{$results}}</span>
    </div>
    <div class="w-full text-sm font-normal text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        @forelse($noti as $key => $value)
            <x-renderer.notification-item-render :dataSource="$value" />
        @empty
            <span class="block w-full px-4 py-2 border rounded border-gray-200 text-red-300">
                Empty Notifications
            </span>
        @endforelse
    </div>
</div>
@endforeach
