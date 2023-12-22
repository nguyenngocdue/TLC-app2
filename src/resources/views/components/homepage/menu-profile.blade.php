<button class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none" @click="toggleProfileMenu" @keydown.escape="closeProfileMenu" aria-label="Account" aria-haspopup="true">
    <x-renderer.avatar-user uid='{{$user->id}}'></x-renderer.avatar-user>
</button>
<template x-if="isProfileMenuOpen">
    <ul x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.away="closeProfileMenu" @keydown.escape="closeProfileMenu" class="absolute right-0 {{$isAdmin ? ' w-[450px]' : 'w-64'}} p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
        @if($isAdmin)
        <div class="grid grid-cols-2">
        @endif
            <div>
                <hr class="my-2" />
                <p class="font-bold text-center w-full text-sm">User</p>
                <li class="flex">
                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="{{route('me.index') }}">
                        <i class="mr-3 h-4 w-4 fa-duotone fa-user"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li class="flex1 hidden ">
                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="#">
                        <i class="mr-3 h-4 w-4 fa-duotone fa-gear"></i>
                        <span>My Settings</span>
                    </a>
                </li>
                @if(!$is3rdParty)
                <li class="flex">
                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" 
                    href="{{route('myOrgChart.index') }}">
                        <i class="mr-3 h-4 w-4 fa-duotone fa-sitemap"></i>
                        <span>My Org Chart</span>
                    </a>
                </li>
                @endif
                @if(!$is3rdParty)
                <li class="flex">
                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" 
                    href="{{route('public-holidays.index') }}">
                        <i class="mr-3 h-4 w-4 fa-duotone fa-calendar-day"></i>
                        <span>Public Holidays</span>
                    </a>
                </li>
                @endif
                @if($stopImpersonate)
                <li class="flex">
                    <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" 
                    href="{{route('setrolesets.stopImpersonate') }}">
                        <i class="mr-3 h-4 w-4 fa-thin fa-circle-stop"></i>
                        <span>Stop Impersonate</span>
                    </a>
                </li>
                @endif
                <li class="flex">
                    <a class="inline-flex w-full items-center rounded-md px-2 py-1 text-sm font-semibold transition-colors duration-150 hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="{{ route('logout') }}" onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                        <i class="mr-3 h-4 w-4 fa-duotone fa-right-from-bracket"></i>
                        <span>Log out</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>
            </div>
            @if($isAdmin)
                @foreach($userMenu as $key => $group)
                    <div>
                    <hr class="my-2" />
                    <p class="font-bold text-center w-full text-sm">{{ \Str::headline($key)}}</p>
                    @foreach ($group as $value)
                        <li class="flex">
                            <a class="inline-flex w-full items-center rounded-md px-2 py-1 text-sm font-semibold transition-colors duration-150 hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="{{ $value['href']??"" }}" target="_blank">
                                @isset($value['icon_fa'])
                                <i class="mr-3 h-4 w-4 {{$value['icon_fa']}}"></i>
                                @else
                                {!! $value['icon']??"" !!}
                                @endisset
                                <span>{{ $value['title'] }}</span>
                            </a>
                        </li>
                    @endforeach
                    </div>
                @endforeach
            @endif
        @if($isAdmin)
        </div>
        @endif
        
    </ul>
</template>
