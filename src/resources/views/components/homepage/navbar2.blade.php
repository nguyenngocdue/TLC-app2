<header class="no-print z-20 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container1 flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
        <x-homepage.mobilehamburger></x-homepage.mobilehamburger>
        <div class="flex justify-center flex-1 lg:mr-32">
           {{--  <x-homepage.searchinput></x-homepage.searchinput> --}}
        </div>
        <ul class="flex items-center flex-shrink-0 space-x-6">
            {{-- <li class="flex">
                <x-homepage.themetoggle></x-homepage.themetoggle>
            </li> --}}
            <li class="flex">
                <x-homepage.settinggear></x-homepage.settinggear>
            </li>
            <li class="relative">
                <x-homepage.menunotification></x-homepage.menunotification>
            </li>
            <li class="relative">
                <x-homepage.menuprofile></x-homepage.menuprofile>
            </li>
        </ul>
    </div>
</header>