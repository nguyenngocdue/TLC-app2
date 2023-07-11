<main class="mt-16 m1b-8 w-full flex-grow dark:bg-gray-700 no-print">
    <div class="w-full h-18 no-print">
        <div class="no-print flex bg-white dark:bg-gray-800 flex-wrap items-center justify-between h-full text-purple-600 dark:text-purple-300">
            <div class="w-full lg:w-1/2 lg:px-6 sm:px-2 md:px-4 flex items-center">
                    <x-renderer.heading level=4 title="@yield('tooltip')">
                        @yield('title', 'Untitled') 
                    </x-renderer.heading>
                    @if(in_array($action, ['edit', /*'show'*/]))
                        <div class="ml-1"><x-renderer.status>@yield('status', '')</x-renderer.status></div>
                    @endif
                    <div class="px-2 text-black">
                        @yield('docId','')
                    </div>
            </div>
            <ul class="w-full lg:w-1/2">
                <li class="relative">
                    <x-navigation.breadcrumb />
                </li>
            </ul>
        </div>
    </div>
</main>