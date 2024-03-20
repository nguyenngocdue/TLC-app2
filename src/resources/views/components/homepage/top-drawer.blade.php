<button class="focus:shadow-outline-purple rounded-md focus:outline-none text-xl" @click="toggleTopDrawer" @keydown.escape="closeTopDrawer">
    <i class="fa-solid fa-bars"></i>
</button>
<template x-if="isTopDrawerOpen">
    <ul x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        @click.away="closeTopDrawer" 
        @keydown.escape="closeTopDrawer" 
        class="absolute left-0 top-16 p-1 spa1ce-y-2 max-h-[740px] w-full text-gray-600 bg-white border-b border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
        <div class="px-1 xl:px-6 h-[300px] md:h-[600px] overflow-y-auto" data-top-drawer >
                    
        </div>
        <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <a href="#" class="inline-flex items-center text-xs font-normal text-gray-500 hover:underline dark:text-gray-300">
                <svg aria-hidden="true" class="w-3 h-3 mr-2" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z"></path></svg>
                Didn't find what you were looking for?</a>
        </div>
    </ul>
    <script>
        dataTopDrawer = document.querySelector("[data-top-drawer]");
        allAppsRecent = allAppsRecent == null ? (@json($allAppsRecent)) : allAppsRecent;
        allApps = allApps == null ? (@json($allApps)) : allApps;
        allAppsTopDrawer = allAppsTopDrawer == null ? (@json($allAppsTopDrawer)) : allAppsTopDrawer;
        buttonTabs =  @json($buttonTabs);
        url = (@json($route));
        renderTopDrawer(buttonTabs,allAppsRecent,filterAllAppCheckAdmin(allAppsTopDrawer),url);
    </script>
</template>



<!-- <button 
data-dropdown-toggle="topDrawerDocument" 
class="focus:shadow-outline-purple rounded-md focus:outline-none text-xl">
<i class="fa-solid fa-bars"></i>
</button>

<div id="topDrawerDocument" 
    class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow min-w-44 p-2 dark:bg-gray-700 dark:divide-gray-600" data-top-drawer>
</div>
<script>
    dataTopDrawer = document.querySelector("[data-top-drawer]");
    allAppsRecent = allAppsRecent == null ? (@json($allAppsRecent)) : allAppsRecent;
    allApps = allApps == null ? (@json($allApps)) : allApps;
    allAppsTopDrawer = allAppsTopDrawer == null ? (@json($allAppsTopDrawer)) : allAppsTopDrawer;
    buttonTabs =  @json($buttonTabs);
    url = (@json($route));
    renderTopDrawer(buttonTabs,allAppsRecent,filterAllAppCheckAdmin(allAppsTopDrawer),url);
</script> -->
    
