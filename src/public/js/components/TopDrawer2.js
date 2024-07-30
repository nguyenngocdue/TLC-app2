function renderOneApp(item, route) {
    const { name, bookmarked, sub_package, href, icon, title } = item
    const bg = bookmarked ? 'hover:bg-pink-700' : 'hover:bg-blue-700'
    const color = bookmarked ? 'text-blue-700' : 'text-gray-400'
    let appItem = ''
    appItem += `<div class="col-span-6 md:col-span-4 xl:col-span-3 2xl:col-span-2 p-2">`
    appItem += `<div data-id="topDrawerBookmarkBtnSpan_${name}"`
    appItem += ` class="relative cursor-pointer w-8 h-8 text-center rounded-full ${bg}"`
    appItem += ` style="top:14%; right: -85%;"`
    appItem += ` onclick="toggleBookmark('${name}', '${sub_package}', '${route}', 'topDrawer')"`
    appItem += `>`
    appItem += `<i data-id="topDrawerBookmarkBtnIcon_${name}" class="fa-duotone fa-bookmark text-2xl ${color}"></i>`
    appItem += `</div>`
    appItem += `<a href="${href}">`
    appItem += `<div class="cursor-pointer hover1:font-bold hover:text-blue-600">`
    appItem += `<div class="flex1 border rounded aspect-square hover:border-blue-200  hover:bg-blue-200">`
    appItem += `<div class="h-8"></div>`
    appItem += `<div class="text-5xl text-center m-auto" style="margin-top: 5%;">`
    appItem += `${icon}`
    appItem += `<div class="text-sm h-8 my-1 px-1">${title}</div>`
    // appItem += `@roleset('admin')`
    // appItem += `<div class="text-xs p-1">(${item["click_count"]})</div>`
    // appItem += `@endroleset`
    appItem += `</div>`
    appItem += `</div>`
    appItem += `</div>`
    appItem += `</a>`
    appItem += `</div>`

    return appItem
}

function topDrawerRenderer(name, div, route) {
    const items = allApps[name]['items']
    // console.log(name, div, items)
    const topDrawerGroup = document.createElement('div')
    topDrawerGroup.id = `topDrawerGroup_${name}`
    topDrawerGroup.classList.add(
        // 'hidden',
        'px-4',
        'top-drawer-group',
        'w-full',
        'sm:w-3/4'
    )
    const grid = document.createElement('div')
    grid.classList.add('grid', 'grid-cols-12', 'application-items')
    let itemStr = ''
    // console.log(items)
    items.forEach((item) => {
        if (!item.hidden_for_non_admin) return
        if (!item.hidden_navbar) return

        itemStr += renderOneApp(item, route)
    })
    grid.innerHTML = itemStr

    topDrawerGroup.appendChild(grid)
    document.getElementById(div).innerHTML = ''
    document.getElementById(div).appendChild(topDrawerGroup)
}
