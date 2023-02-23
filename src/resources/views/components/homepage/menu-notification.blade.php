<button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple" @click="toggleNotificationsMenu" @keydown.escape="closeNotificationsMenu" aria-label="Notifications" aria-haspopup="true">
    <i class="fa-duotone fa-bell"></i>
    <span aria-hidden="true" class="absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full dark:border-gray-800"></span>
</button>
<template x-if="isNotificationsMenuOpen">
    <div x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.away="closeNotificationsMenu" @keydown.escape="closeNotificationsMenu" class="absolute right-0 w-[700px] p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
        <div class="grid grid-cols-3 gap-2 w-full">
            <div class="w-full h-full">
                <span class="text-base font-semibold flex justify-center">Assignee To Me</span>
                <ul>
                    @forelse($assigneeNotifications as $key => $value)
                        <x-renderer.notification-item :dataSource="$value" />
                    @empty
                    {{-- <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex items-center ">
                            <i class="mr-2 fa-duotone fa-flag-swallowtail"></i>
                            <p class="text-sm">No notifications.</p>
                        </div>
                    </div> --}}
                    @endforelse
                </ul>
            </div>
            <div>
                <span>Created To Me</span>
                <ul >
                    @forelse($createdNotifications as $key => $value)
                        <x-renderer.notification-item :dataSource="$value" />
                    @empty
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex items-center ">
                            <i class="mr-2 fa-duotone fa-flag-swallowtail"></i>
                            <p class="text-sm">No notifications.</p>
                        </div>
                    </div>
                    @endforelse
                </ul>
            </div>
            <div>
                <span>Monitor To Me</span>
            <ul>
                
                    @forelse($monitorNotifications as $key => $value)
                    <x-renderer.notification-item :dataSource="$value" />
                    @empty
                    {{-- <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex items-center ">
                            <i class="mr-2 fa-duotone fa-flag-swallowtail"></i>
                            <p class="text-sm">No notifications.</p>
                        </div>
                    </div> --}}
                    @endforelse
                </ul>
            </div>

        </div>

    </div>
    
    
</template>
