// function data() {
//     function getThemeFromLocalStorage() {
//         // if user already changed the theme, use it
//         if (window.localStorage.getItem('dark')) {
//             return JSON.parse(window.localStorage.getItem('dark'))
//         }
//         // else return their preferences
//         return (
//             !!window.matchMedia &&
//             window.matchMedia('(prefers-color-scheme: dark)').matches
//         )
//     }

//     function setThemeToLocalStorage(value) {
//         window.localStorage.setItem('dark', value)
//     }

//     return {
//         dark: getThemeFromLocalStorage(),
//         toggleTheme() {
//             this.dark = !this.dark
//             setThemeToLocalStorage(this.dark)
//         },
//         isSideMenuOpen: false,
//         toggleSideMenu() {
//             this.isSideMenuOpen = !this.isSideMenuOpen
//         },
//         closeSideMenu() {
//             this.isSideMenuOpen = false
//         },
//         isNotificationsMenuOpen: false,
//         toggleNotificationsMenu() {
//             this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
//         },
//         closeNotificationsMenu() {
//             this.isNotificationsMenuOpen = false
//         },
//         isProfileMenuOpen: false,
//         toggleProfileMenu() {
//             this.isProfileMenuOpen = !this.isProfileMenuOpen
//         },
//         closeProfileMenu() {
//             this.isProfileMenuOpen = false
//         },
//         isPagesMenuOpen: false,
//         togglePagesMenu() {
//             this.isPagesMenuOpen = !this.isPagesMenuOpen
//         },
//         // Modal
//         isModalOpen: false,
//         trapCleanup: null,
//         openModal() {
//             this.isModalOpen = true
//             this.trapCleanup = focusTrap(document.querySelector('#modal'))
//         },
//         closeModal() {
//             this.isModalOpen = false
//             this.trapCleanup()
//         },
//     }
// }
// const setup = () => {
//     const getTheme = () => {
//         if (window.localStorage.getItem('dark')) {
//             return JSON.parse(window.localStorage.getItem('dark'))
//         }
//         return (
//             !!window.matchMedia &&
//             window.matchMedia('(prefers-color-scheme: dark)').matches
//         )
//     }

//     const setTheme = (value) => {
//         window.localStorage.setItem('dark', value)
//     }

//     return {
//         loading: true,
//         isDark: getTheme(),
//         toggleTheme() {
//             this.isDark = !this.isDark
//             setTheme(this.isDark)
//         },
//         setLightTheme() {
//             this.isDark = false
//             setTheme(this.isDark)
//         },
//         setDarkTheme() {
//             this.isDark = true
//             setTheme(this.isDark)
//         },
//         watchScreen() {
//             if (window.innerWidth <= 1024) {
//                 this.isSidebarOpen = false
//                 this.isSecondSidebarOpen = false
//             } else if (window.innerWidth >= 1024) {
//                 this.isSidebarOpen = true
//                 this.isSecondSidebarOpen = true
//             }
//         },
//         isSidebarOpen: window.innerWidth >= 1024 ? true : false,
//         toggleSidbarMenu() {
//             this.isSidebarOpen = !this.isSidebarOpen
//         },
//         isSecondSidebarOpen: window.innerWidth >= 1024 ? true : false,
//         toggleSecondSidbarColumn() {
//             this.isSecondSidebarOpen = !this.isSecondSidebarOpen
//         },
//         isSettingsPanelOpen: false,
//         openSettingsPanel() {
//             this.isSettingsPanelOpen = true
//             this.$nextTick(() => {
//                 this.$refs.settingsPanel.focus()
//             })
//         },
//         isSearchPanelOpen: false,
//         openSearchPanel() {
//             this.isSearchPanelOpen = true
//             this.$nextTick(() => {
//                 this.$refs.searchInput.focus()
//             })
//         },
//     }
// }
function toggleModalSetting(modalID) {
    document.getElementById(modalID).classList.toggle('hidden')
    // document.getElementById(modalID + '-backdrop').classList.toggle('hidden')
    document.getElementById(modalID).classList.toggle('flex')
    // document.getElementById(modalID + '-backdrop').classList.toggle('flex')
}
