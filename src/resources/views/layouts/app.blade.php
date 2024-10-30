<!DOCTYPE html>
{{-- <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script> --}}

<script src="{{ asset('js/jquery@3.7.1.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script>
    let wsClientId
    function check() {
        const host = window.location.hostname
        const url = 'https://' + host + '/api/v1/system/app_version'
        $.ajax({
            type: 'get',
            url:  url,
                    success: function (response) {
                        if (response.success) {
                            const versionServer = response.hits
                            wsClientId = response.meta.ws_client_id
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
<html :class="{ 'dark': isDark }" x-data="alpineData()" x-ref="alpineRef" lang="en">

@include("layouts/head")
@include("layouts/theme-css")

<body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div id="loading-animation" class="w-full h-screen justify-center items-center flex text-5xl" style="display: none">
            <i class="fa-duotone fa-spinner fa-spin text-green-500"></i>
            <span class="text-lg ml-2 text-green-500">Updating to the latest version</span>
        </div>
        <div id="content-app" :class="{ 'overflow-hidden': isSideMenuOpen }">
            <div class="flex flex-col flex-1 w-full">
                <x-homepage.navbar2 />
                    @auth <x-renderer.page-header /> @endauth
                    @guest <div class="mt-16 mb-8 no-print"></div> @endguest
                    <div id="print-pdf-document"  class="w-full min-h-sc1reen h-full MUST-NOT-HAVE-X-PADDING-MARGIN-FOR-PRINT-PAGE">
                        @yield('content')
                    </div>
                    {{-- <div class="mt-8 no-print"></div> --}}
            </div> 
        </div>
        {{-- Button Go to Top and Bottom --}}
        <x-renderer.button-scroll />
        {!! toastr()->message() !!}
        <script>
            window.Echo.channel('wss-demo-channel')
                .listen('WssDemoChannel', (e) => {
                    console.log(e.data);
                })
        </script>
        <script>
            const app_env = '{{env('APP_DOMAIN')}}';
            window.Echo.channel('wss-toastr-message-channel-' + app_env)
                .listen('WssToastrMessageChannel', (e) => {
                    wsClientId1 = e.data.wsClientId
                    if(wsClientId !== wsClientId1) return //<<Fire only on current tab.
                    switch (e.data.type){
                        case "success":
                            toastr.success(e.data.message, e.data.code)
                            break
                        case "error":
                            toastr.error(e.data.message, e.data.code)
                            break
                        case "warning":
                            toastr.warning(e.data.message, e.data.code)
                            break
                        default:
                        case "info":
                            toastr.info(e.data.message, e.data.code)
                            break
                    }
                })
        </script>
</body>
</html>

<x-renderer.app-footer />
