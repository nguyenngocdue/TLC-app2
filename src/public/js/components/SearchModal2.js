function toggleBookmark(appIdSingular) {
    console.log('Toggle bookmark', appIdSingular)
}

function searchModelShowAll() {
    console.log('Show all apps')
}

function searchModelShowFiltered(keywords) {
    console.log('Show filtered apps', keywords)
}

function onSearchModalInit() {
    dataContainer = document.querySelector('[data-modal-container]')
    searchInput = document.querySelector('[data-search-input]')
    searchInput.focus()
    // console.log(dataContainer)
    // console.log(searchInput)

    searchModelShowAll()
    searchInput.addEventListener('input', (e) => {
        const value = e.target.value.toLowerCase()
        if (value.length == 0) {
            searchModelShowAll()
        } else {
            searchModelShowFiltered(value)
        }
    })
}

// console.log('SearchModal2.js loaded')
