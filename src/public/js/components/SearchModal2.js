function oneAppRenderer(app, route) {
    const tempContainer = document.createElement('div')
    let result = ''
    result += `<div class="flex w-full">`
    result += `<a href="${app.href}" class="rounded flex w-full justify-between text-xs px-2 py-3 my-1 bg-gray-100 cursor-pointer hover:bg-gray-200 text-gray-700">`
    result += `<span class="left-group">`
    result += `<span class="px-2 w-10">${app.icon}</span>`
    result += `${app.title}`
    result += `</span>`
    result += `<span class="right-group">`
    if (app.hidden) result += `<i class="fa-duotone fa-eye text-blue-600 px-2"></i>`
    result += `</span>`
    result += `</a>`
    const bookmarkBgColor = app.bookmarked ? 'hover:bg-pink-700' : 'hover:bg-blue-700'
    result += `<span id="searchModalBookmarkBtnSpan_${app.name}" class="${bookmarkBgColor} rounded px-2 py-2 my-1 cursor-pointer" onclick="toggleBookmark('${app.name}', '${app.sub_package}', '${route}', 'searchModal')">`
    const bookmarkColor = app.bookmarked ? 'text-blue-600' : 'text-gray-400'
    result += `<i id="searchModalBookmarkBtnIcon_${app.name}" class="fa-duotone fa-bookmark ${bookmarkColor}"></i>`
    result += `</span>`
    result += `</div>`
    tempContainer.innerHTML = result
    return tempContainer.firstChild
}

function searchModelShowFiltered(route, keywords = null) {
    // console.log('Show filtered apps', keywords)
    var regex = null
    if (keywords) regex = new RegExp(keywords.replaceAll(' ', '.*'), 'i')

    dataContainer = document.querySelector('[data-container]')
    let result = {}
    let totalResultCount = 0
    Object.keys(allApps).forEach((key) => {
        const title = allApps[key].title
        result[key] = {}
        result[key].title = title
        result[key].str = ''
        result[key].click_count = 0
        const items = allApps[key].items
        for (let i = 0; i < items.length; i++) {
            const app = items[i]
            const fn = () => oneAppRenderer(app, route)
            if (regex) {
                const str = [app.title, app.name, app.nickname].join(' ')
                if (regex.test(str)) {
                    result[key].str += fn().outerHTML
                    result[key].click_count += app.click_count
                    totalResultCount++
                }
            } else {
                result[key].str += fn().outerHTML
                result[key].click_count += app.click_count
                totalResultCount++
            }
        }
        if (!result[key].str) delete result[key]
    })
    // console.log('Total result count', totalResultCount)
    result = Object.values(result).sort((a, b) => b.click_count - a.click_count)
    // console.log('Result', result)

    let finalResult = ''
    Object.keys(result).forEach((key) => {
        finalResult += `<h2 class='font-bold p-1 text-black'>${result[key].title}</h2>`
        finalResult += result[key].str
    })
    $('#searchModalTotalAppCount').text(totalResultCount)
    dataContainer.innerHTML = finalResult
}

function onSearchModalInit(updateSettingRoute) {
    searchInput = document.querySelector('[data-search-input]')
    searchInput.focus()

    searchModelShowFiltered(updateSettingRoute)
    searchInput.addEventListener('input', (e) => {
        const value = e.target.value.toLowerCase()
        searchModelShowFiltered(updateSettingRoute, value)
    })
}
