@php
use App\Utils\Support\CurrentRoute;
$viewAll = CurrentRoute::getTypePlural();
$routeSrc = Route::has($viewAll.".index") ? route($viewAll.".index") : "#NotFound:".$viewAll.".index";
@endphp

<header class="h-16 no-print fixed w-full z-20 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container1 flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
        {{-- <x-homepage.mobile-hamburger></x-homepage.mobile-hamburger> --}}
        <x-homepage.logo />
        @auth
        <div class="flex flex-1 lg:mr-32 ml-5">
            <div class="mr-5">
                <x-homepage.top-drawer/>
            </div>
            <b class="text-xl font-semibold">
                <a href="{{$routeSrc}}" class="hover:underline">
                    @yield('topTitle', 'Untitled')
                </a>
            </b>
            
        </div>
        
        {{-- <div class="flex justify-center flex-1 lg:mr-32">            
            <x-homepage.search-input></x-homepage.search-input>
        </div> --}}
        <ul class="flex items-center flex-shrink-0 space-x-2 border1">
            <li class="flex border1 px-2" title="Hot Key: Alt + Q">
                <x-homepage.search-modal modalId="modal-search-app" />
            </li>
            <!-- <li class="flex">
                <x-homepage.theme-toggle></x-homepage.theme-toggle>
            </li> -->
            {{-- @if(env('APP_ENV') === 'local')
            <li class="relative">
                <x-homepage.setting-gear/>
            </li>
            <li class="relative">
                <x-homepage.menu-notification/>
            </li>
            @endif --}}
            <li class="relative border1 px-2">
                <x-homepage.menu-theme/>
            </li>
            <li class="flex border1" title="Projects">
                <x-homepage.menu-project-filter/>
            </li>
            <li class="relative border1">
                <x-homepage.menu-profile/>
            </li>
        </ul>
        @endauth

    </div>
</header>