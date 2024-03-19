<button 
    class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none" 
    @click="toggleThemeMenu" 
    @keydown.escape="closeThemeMenu" 
    aria-label="Account" 
    aria-haspopup="true">
    <i class="fa-duotone fa-palette"></i>
</button>

<template x-if="isThemeMenuOpen">
    <ul 
        x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        @click.away="closeThemeMenu" 
        @keydown.escape="closeThemeMenu" 
        class="absolute right-0 p-2 mt-2 spa1ce-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" 
        aria-label="submenu">
        
        <li class="flex">
            <x-homepage.menu-theme-item color="gray" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="amber" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="yellow" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="lime" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="emerald" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="teal" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="cyan" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="sky" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="blue" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="indigo" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="violet" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="purple" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="fuchsia" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="pink" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
        <li class="flex">
            <x-homepage.menu-theme-item color="rose" currentBg="{{$themeBg}}" route={{$route}}/>
        </li>
    </ul>
</template>
