<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script href="{{ asset('js/app.js') }}"></script>
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- Sidebar -->
        <x-sidebar />
        <div class="flex-0 flex w-full flex-col">
            <!-- Navbar -->
            <x-navbar />
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {!! Toastr::message() !!}
    <script type="text/javascript" src="{{ asset('js/tlc.js') }}"></script>
    <script type="text/javascript">
        function data() {
            function getThemeFromLocalStorage() {
                // if user already changed the theme, use it
                if (window.localStorage.getItem('dark')) {
                    return JSON.parse(window.localStorage.getItem('dark'))
                }
                // else return their preferences
                return (
                    !!window.matchMedia &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches
                )
            }

            function setThemeToLocalStorage(value) {
                window.localStorage.setItem('dark', value)
            }

            return {
                dark: getThemeFromLocalStorage()
                , toggleTheme() {
                    this.dark = !this.dark
                    setThemeToLocalStorage(this.dark)
                }
                , isSideMenuOpen: false
                , toggleSideMenu() {
                    this.isSideMenuOpen = !this.isSideMenuOpen
                }
                , closeSideMenu() {
                    this.isSideMenuOpen = false
                }
                , isNotificationsMenuOpen: false
                , toggleNotificationsMenu() {
                    this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
                }
                , closeNotificationsMenu() {
                    this.isNotificationsMenuOpen = false
                }
                , isProfileMenuOpen: false
                , toggleProfileMenu() {
                    this.isProfileMenuOpen = !this.isProfileMenuOpen
                }
                , closeProfileMenu() {
                    this.isProfileMenuOpen = false
                }
                , isPagesMenuOpen: false
                , togglePagesMenu() {
                    this.isPagesMenuOpen = !this.isPagesMenuOpen
                },
                // Modal
                isModalOpen: false
                , trapCleanup: null
                , openModal() {
                    this.isModalOpen = true
                    this.trapCleanup = focusTrap(document.querySelector('#modal'))
                }
                , closeModal() {
                    this.isModalOpen = false
                    this.trapCleanup()
                }
            , }
        }

    </script>
    <script>
        const setup = () => {
            const getTheme = () => {
                if (window.localStorage.getItem("dark")) {
                    return JSON.parse(window.localStorage.getItem("dark"));
                }
                return (
                    !!window.matchMedia &&
                    window.matchMedia("(prefers-color-scheme: dark)").matches
                );
            };

            const setTheme = (value) => {
                window.localStorage.setItem("dark", value);
            };

            return {
                loading: true
                , isDark: getTheme()
                , toggleTheme() {
                    this.isDark = !this.isDark;
                    setTheme(this.isDark);
                }
                , setLightTheme() {
                    this.isDark = false;
                    setTheme(this.isDark);
                }
                , setDarkTheme() {
                    this.isDark = true;
                    setTheme(this.isDark);
                }
                , watchScreen() {
                    if (window.innerWidth <= 1024) {
                        this.isSidebarOpen = false;
                        this.isSecondSidebarOpen = false;
                    } else if (window.innerWidth >= 1024) {
                        this.isSidebarOpen = true;
                        this.isSecondSidebarOpen = true;
                    }
                }
                , isSidebarOpen: window.innerWidth >= 1024 ? true : false
                , toggleSidbarMenu() {
                    this.isSidebarOpen = !this.isSidebarOpen;
                }
                , isSecondSidebarOpen: window.innerWidth >= 1024 ? true : false
                , toggleSecondSidbarColumn() {
                    this.isSecondSidebarOpen = !this.isSecondSidebarOpen;
                }
                , isSettingsPanelOpen: false
                , openSettingsPanel() {
                    this.isSettingsPanelOpen = true;
                    this.$nextTick(() => {
                        this.$refs.settingsPanel.focus();
                    });
                }
                , isSearchPanelOpen: false
                , openSearchPanel() {
                    this.isSearchPanelOpen = true;
                    this.$nextTick(() => {
                        this.$refs.searchInput.focus();
                    });
                }
            , };
        };

    </script>
</body>



</html>
