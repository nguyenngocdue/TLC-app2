<button class="focus:shadow-outline-purple rounded-md focus:outline-none text-xl p-2 hover:bg-gray-50 hover:border-gray-200 border-transparent border" 
    @click="toggleTopDrawer" 
    @keydown.escape="closeTopDrawer"
    >
    <i class="fa-solid fa-bars"></i>
</button>
<template x-if="isTopDrawerOpen">
    <ul x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        @click.away="closeTopDrawer" 
        @keydown.escape="closeTopDrawer" 
        class="absolute left-0 top-16 p-1 w-full text-gray-600 bg-white border-b border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" 
        aria-label="submenu"
        style="max-height: 70vh; height:70vh;"
        >
        <div data-top-drawer class="px-1 xl:px-6 overflow-y-auto" style="max-height: 63vh; height:63vh;">
            <x-renderer.tab-pane :tabs="$tabPans">
                <x-homepage.top-drawer2-applications :dataSource="$dataSource" />
                <x-homepage.top-drawer2-reports />
                <x-homepage.top-drawer2-documents />
                <div class="block h-4"></div>
            </x-renderer.tab-pane>
            <div class="block h-1"></div>
        </div>
        <div class="flex items-center hover:text-orange-400 text-gray-500 p-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <a href="https://appcenter.tlcmodular.com" class="inline-flex gap-2 items-center text-xs font-normal hover:underline dark:text-gray-300">
                <i class="fa-duotone fa-circle-question"></i>
                Tutorial Resources
            </a>
        </div>
    </ul>    
</template>