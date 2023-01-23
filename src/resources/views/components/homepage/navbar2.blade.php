<header class="no-print z-20 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container1 flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
        <x-homepage.mobile-hamburger></x-homepage.mobile-hamburger>
        <div class="flex flex-1 lg:mr-32">
            <b>@yield('topTitle', 'Untitled')</b>
        </div>
        <div class="flex justify-center flex-1 lg:mr-32">            
           {{--  <x-homepage.search-input></x-homepage.search-input> --}}
        </div>
        <ul class="flex items-center flex-shrink-0 space-x-6">
            {{-- <li class="flex">
                <x-homepage.theme-toggle></x-homepage.theme-toggle>
            </li> --}}
            <li class="flex">
                <x-homepage.setting-gear></x-homepage.setting-gear>
            </li>
            <li class="relative">
                <x-homepage.menu-notification></x-homepage.menu-notification>
            </li>
            <li class="relative">
                <x-homepage.menu-profile></x-homepage.menu-profile>
            </li>
        </ul>
    </div>
</header>