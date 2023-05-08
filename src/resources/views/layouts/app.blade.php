<!DOCTYPE html>
<script>
    function check() {
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
        }
        return false;
    }
    const isDark =  check();
    if(isDark){
        var root = document.getElementsByTagName('html')[0];
        root.classList.add('dark');
    }
</script>
<html :class="{ 'dark': dark }" x-data="data()" lang="en">

@include("layouts/head")

<body >
    <div  class="bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        {{-- <x-homepage.sidebar2 /> --}}
        <div class="flex flex-col flex-1 w-full">
            <x-homepage.navbar2 />
            <x-elapse title="Nav bar: " />
            @auth
            <main class="mt-16 m1b-8 w-full flex-grow dark:bg-gray-700 no-print">
                <div class="w-full h-18 no-print">
                    <div class="no-print flex bg-white dark:bg-gray-800 flex-wrap items-center justify-between h-full text-purple-600 dark:text-purple-300">
                        <div class="w-full lg:w-1/2 lg:px-6 sm:px-2 md:px-4 flex">
                            <x-renderer.heading level=4>
                                @yield('title', 'Untitled')
                            </x-renderer.heading>
                            @if(isset($type))
                            <x-renderer.heading-status :type="$type">
                                @yield('status', 'Untitled')
                            </x-renderer.heading-status>
                            @endif
                        </div>
                        <ul class="w-full lg:w-1/2">
                            <li class="relative">
                                <x-navigation.breadcrumb />
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
            @endauth
            @guest
                <div class="mt-16 mb-8 no-print">
                </div>
            @endguest
            <x-elapse title="Title bar: "/>
            <div id="print-pdf-document"  class="w-full min-h-screen MUST-NOT-HAVE-X-PADDING-MARGIN-FOR-PRINT-PAGE">
                @yield('content')
            </div>
            <div class="mt-8 no-print"></div>
        </div>
    </div>
        {!! Toastr::message() !!}
</body>

</html>
