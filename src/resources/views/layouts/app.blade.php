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
<html :class="{ 'dark': dark }" x-data="alpineData()" lang="en">

@include("layouts/head")

<body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div id="loading-animation" class="w-full h-screen justify-center items-center flex text-5xl" style="display: none">
            <i class="fa-duotone fa-spinner fa-spin text-green-500"></i>
            <span class="text-lg ml-2 text-green-500">Updating to new version</span>
        </div>
        <div id="content-app"  class="bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
            <div class="flex flex-col flex-1 w-full">
                <x-homepage.navbar2 />
                @auth <x-renderer.page-header /> @endauth
                @guest <div class="mt-16 mb-8 no-print"></div> @endguest
                <div id="print-pdf-document"  class="w-full min-h-sc1reen h-full MUST-NOT-HAVE-X-PADDING-MARGIN-FOR-PRINT-PAGE">
                    @yield('content')
                </div>
                <div class="mt-8 no-print"></div>
            </div> 
        </div>
        {{-- Button Go to Top and Bottom --}}
        <x-renderer.button-scroll />
        {!! Toastr::message() !!}
        <script>
            function numberToWords(number){
                if(typeof number == 'string') number = number.replace(/[^0-9.\-]/g, '');
                number = (number*1).toString() * 1;
                return capitalizeFirstLetter(window.toWords(number) + readDecimalPart(number));
            }
            function capitalizeFirstLetter(str) {
                return str.replace(/(^|\s)\w/g, (match) => match.toUpperCase());
            }
            function readDecimalPart(number) {
                const numberString = number + '';
                const decimalPart = numberString.split('.')[1];
                if((decimalPart * 1 == 0)) return '';
                if (!decimalPart) {
                    return '';
                }
                let result = ['point'];
                for (let i = 0; i < decimalPart.length; i++) {
                    const digit = parseInt(decimalPart[i]);
                    const word = convertDigitToWord(digit);
                    result.push(word);
                }
            return ' ' + result.join(' ');
            }
            function convertDigitToWord(digit) {
            const digitsMap = {
                '0': 'zero',
                '1': 'one',
                '2': 'two',
                '3': 'three',
                '4': 'four',
                '5': 'five',
                '6': 'six',
                '7': 'seven',
                '8': 'eight',
                '9': 'nine',
            };
            return digitsMap[digit];
            }
        </script>
        <script>
            window.Echo.channel('test')
                .listen('.Test', (e) => {
                    console.log(e.data);
                })
        </script>
</body>
</html>

<x-renderer.app-footer />
