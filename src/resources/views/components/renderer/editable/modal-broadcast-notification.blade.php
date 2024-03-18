<button type="hidden" class="hidden buttonToggleNotification" @click="toggleBroadcastNotification">
</button>
<template x-if="isBroadcastNotificationOpen">
    <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden="true">
        <div class="relative sm:mx-5 md:mx-24 w-full lg:mx-56 xl:mx-80 2xl:mx-[500px] h-auto md:h-auto sm:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            This document has a newer version
                        </h3>
                        <button type="button" @click="closeBroadcastNotification()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <div class='p-4'>
                    <span class="userNameNotification"></span>
                    <span class="text-base font-normal">
                         has changed the content of this document.Please reload to apply the new version.
                    </span>
                    <p class="text-red-300 font-normal text-sm">Note: All your change will be lost.</p>
                </div>
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                    @php
                       $classButton =  App\Utils\ClassList::BUTTON2
                    @endphp
                    <button href="#" class="{{$classButton}}" onclick="window.location.reload()">Reload</button>
                </div>
            </div>
        </div>
    </div>
</template>