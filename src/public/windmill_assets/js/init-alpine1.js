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
        isSearchMenuOpen: false,
        toggleSearchMenu() {
            this.isSearchMenuOpen = !this.isSearchMenuOpen
        },
        closeSearchMenu() {
            this.isSearchMenuOpen = false
            //Using remove event listener file search-modal.blade.php
            searchInput.removeEventListener('input', () => {})
        },
        isSettingMenuOpen: false,
        toggleSettingMenu() {
            this.isSettingMenuOpen = !this.isSettingMenuOpen
        },
        closeSettingMenu() {
            this.isSettingMenuOpen = false
        },
        isIntermediateOpen: {},
        toggleIntermediate(type) {
            const status =
                document.getElementById('status') ??
                document.getElementById('select-dropdown-status')
            status.value = type
            this.isIntermediateOpen[type] = !this.isIntermediateOpen[type]
        },
        closeIntermediate(type) {
            this.isIntermediateOpen[type] = false
        },
        changeStatus(type) {
            const status =
                document.getElementById('status') ??
                document.getElementById('select-dropdown-status')
            status.value = type
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
        trapCleanup: null,
        // isModalOpen: false,
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
            // console.log("Open [" + id + "] called")
            this.isModalOpenArray[id] = true
            // console.log("Open [" + id + "] called1")
            this.trapCleanup = focusTrap(document.querySelector('#modal'))
            // console.log("Open [" + id + "] called2")
            document.getElementById(id).classList.remove('hidden')
            // console.log("Open [" + id + "] called3")
            document.getElementById(id).classList.add('flex')
        },
        closeModal(id) {
            // console.log("Close [" + id + "] called")
            this.isModalOpenArray[id] = false
            this.trapCleanup()
            document.getElementById(id).classList.add('hidden')
            document.getElementById(id).classList.remove('flex')
        },
    }
}
