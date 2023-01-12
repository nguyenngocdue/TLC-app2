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
        dark: getThemeFromLocalStorage(),
        toggleTheme() {
            this.dark = !this.dark
            setThemeToLocalStorage(this.dark)
        },
        isSideMenuOpen: false,
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen
        },
        closeSideMenu() {
            this.isSideMenuOpen = false
        },
        isNotificationsMenuOpen: false,
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
        },
        closeNotificationsMenu() {
            this.isNotificationsMenuOpen = false
        },
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false
        },
        // isPagesMenuOpen: false,
        // togglePagesMenu() {
        //     this.isPagesMenuOpen = !this.isPagesMenuOpen
        // },
        // openingMenu: '',
        // isMenuOpen(type) {
        //     return type;
        // },
        // toggleMenu(type) {
        //     console.log(type, this.openingMenu)
        // },
        // Modal
        // isModalOpen: false,
        trapCleanup: null,
        // openModal() {
        //     this.isModalOpen = true
        //     this.trapCleanup = focusTrap(document.querySelector('#modal'))
        // },
        // closeModal() {
        //     this.isModalOpen = false
        //     this.trapCleanup()
        // },

        //ModalArray
        isModalOpenArray: {},
        openModal(id) {
            // console.log("Open called")
            this.isModalOpenArray[id] = true
            this.trapCleanup = focusTrap(document.querySelector('#modal'))
            document.getElementById(id).classList.toggle('hidden')
            document.getElementById(id).classList.toggle('flex')
        },
        closeModal(id) {
            // console.log("Close called")
            this.isModalOpenArray[id] = false
            this.trapCleanup()
            document.getElementById(id).classList.toggle('hidden')
            document.getElementById(id).classList.toggle('flex')
        },
    }
}
