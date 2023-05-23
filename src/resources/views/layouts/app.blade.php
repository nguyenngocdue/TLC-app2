<!DOCTYPE html>
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script>
    function check() {
        const host = window.location.hostname
        const url = 'https://' + host + '/api/v1/system/app_version'
        $.ajax({
            type: 'get',
            url:  url,
                    success: function (response) {
                        if (response.success) {
                            const versionServer = response.hits
                            if (window.localStorage.getItem('version')) {
                                const versionLocal = window.localStorage.getItem('version')
                                if(versionLocal != versionServer){
                                    window.localStorage.setItem('version',versionServer)
                                    $('#content-app').hide();
                                    $('#loading-animation').show();
                                    setTimeout(() => {
                                        location.reload(true);
                                    }, 1);
                                }
                            }else{
                                window.localStorage.setItem('version',versionServer)
                            }
                        } 
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
        },
     })
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
    <div id="loading-animation" class="w-full h-screen justify-center items-center flex text-5xl" style="display: none">
        <i class="fa-duotone fa-spinner fa-spin text-green-500"></i>
        <span class="text-lg ml-2 text-green-500">Updating to new version</span>
    </div>
    <div id="content-app"  class="bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        {{-- <x-homepage.sidebar2 /> --}}
        <div class="flex flex-col flex-1 w-full">
            <x-homepage.navbar2 />
            @auth
            <main class="mt-16 m1b-8 w-full flex-grow dark:bg-gray-700 no-print">
                <div class="w-full h-18 no-print">
                    <div class="no-print flex bg-white dark:bg-gray-800 flex-wrap items-center justify-between h-full text-purple-600 dark:text-purple-300">
                        <div class="w-full lg:w-1/2 lg:px-6 sm:px-2 md:px-4 flex items-center">
                                <x-renderer.heading level=4>
                                    @yield('title', 'Untitled')
                                </x-renderer.heading>
                                @if(isset($type))
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
            @endauth
            @guest
                <div class="mt-16 mb-8 no-print">
                </div>
            @endguest
            <div id="print-pdf-document"  class="w-full min-h-screen MUST-NOT-HAVE-X-PADDING-MARGIN-FOR-PRINT-PAGE">
                @yield('content')
            </div>
            <div class="mt-8 no-print"></div>
        </div>
    </div>
        {!! Toastr::message() !!}
</body>
<script>
    window.Echo.channel('test')
        .listen('.Test', (e) => {
            console.log(e);
        })
</script>
</html>

<x-renderer.app-footer />