let calendarContainer = null
let modalContainer = null
let dataContainer = null
let dataTopDrawer = null
let searchInput = null
let allApps = null
let allAppsTopDrawer = null
let currentUserIsAdmin = null
let apps = []
let appsTopDrawer = []

const renderHtml = (appsRender, url, topDrawer) => {
    if (topDrawer) {
        dataTopDrawer.innerHTML = ``
        let resultHtmlTopDrawer = ``
        for (const app_index in appsRender) {
            let resultHtml = ``
            const nameApp = capitalize(app_index ?? '')
            const apps = appsRender[app_index]
            for (const property in apps) {
                const lengthGroup = Object.keys(apps[property]).length
                let totalPackage = 0
                const package = property
                let html = ``
                let htmlGroup = ``
                let htmlFlexCol = ``
                let count = 1
                let countDivision = 0
                for (const value in apps[property]) {
                    let htmlTopDrawer = ``
                    const sub_package = value
                    let total = 0
                    apps[property][value].forEach((app) => {
                        total += app.click_count ? app.click_count : 0
                        const status = capitalize(app.status ?? '')
                        const isBookmark = app.bookmark
                            ? 'text-blue-500'
                            : 'text-gray-300'
                        const isCreate = app.href_create
                            ? `<a href="${app.href_create}" class="flex flex-1 items-center text-orange-400">
                            <i class="fa-light fa-circle-plus"></i>
                        </a>`
                            : ''
                        const click_count = app.click_count
                            ? `<span class="inline-flex items-center justify-center px-2 mr-2 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-300">${app.click_count}</span>`
                            : ''
                        const statusHtml = status
                            ? `<span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-300">${status}</span>`
                            : ''
                        htmlTopDrawer += `
                        <li>
                            <div class='flex p-2 text-xs font-medium  text-gray-600 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white'>
                                <button tabIndex=-1 id='bookmark_${
                                    app.name
                                }' onclick="bookmarkSearchModal('${
                            app.name
                        }','${url}')" class='px-2 text-base ${isBookmark}'>
                        <i class="fa-solid fa-bookmark"></i></button>
                                <a href="${
                                    app.href
                                }" class="flex flex-1 px-2 items-center ">
                                ${
                                    app.icon ??
                                    "<i class='fa-light fa-file'></i>"
                                }
                                        <span class="flex-1 ml-3 whitespace-nowrap">${
                                            app.title
                                        }</span>
                                        ${statusHtml}
                                </a>
                                ${click_count}
                                ${isCreate}
                                
                            </div>
                        </li>`
                    })
                    totalPackage += total
                    const totalHtml = total
                        ? `<span class="ml-2 inline-flex items-center justify-center px-2 mr-2 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-300">${total}</span>`
                        : ''
                    html += `<li class='px-2'>
                                    <p class="p-2 text-sm font-medium text-gray-900 dark:text-gray-300">${sub_package} ${totalHtml}</p>
                                        <ul class="space-y-1">
                                           ${htmlTopDrawer}
                                        </ul>
                            </li>`
                    if (lengthGroup >= 2) {
                        const divisionPra = Math.ceil(lengthGroup / 2)
                        if (count % 2 === 0) {
                            htmlFlexCol += `<ul class="flex flex-col">
                                            ${html}
                                            </ul>`
                            html = ``
                            countDivision++
                        } else {
                            if (count === lengthGroup) {
                                htmlFlexCol += `<ul class="flex flex-col">
                                            ${html}
                                            </ul>`
                                html = ``
                                countDivision++
                            }
                        }
                    } else {
                        htmlFlexCol = html
                    }
                    count++
                }
                const totalPackageHtml = totalPackage
                    ? `<span class="ml-2 inline-flex items-center justify-center px-2 mr-2 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-300">${totalPackage}</span>`
                    : ''
                switch (lengthGroup) {
                    case 1:
                    case 2:
                        htmlGroup = `<ul class="grid grid-cols-1">
                                            ${htmlFlexCol}
                                         </ul>`
                        break
                    case 3:
                    case 4:
                        htmlGroup = `<ul class="grid grid-cols-2">
                                                ${htmlFlexCol}
                                    </ul>`
                        break
                    case 5:
                    default:
                        htmlGroup = `<ul class="grid grid-cols-3">
                                                ${htmlFlexCol}
                                    </ul>`
                        break
                }
                resultHtml += `<div class='px-2'>
                                        <p class="p-2 text-sm font-medium text-gray-900 dark:text-gray-300">${package} ${totalPackageHtml}</p>
                                            ${htmlGroup}
                                </div>`
                // <ul class="grid grid-rows-2 grid-flow-col">
            }
            resultHtmlTopDrawer += `<div class="px-2 border-b border-gray-700 py-5">
                                        <p class="p-1 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            ${nameApp}
                                        </p>
                                        <div class="grid grid-rows-auto grid-flow-col">
                                            <div class='flex'>${resultHtml}</div>
                                        </div>
                                    </div>`
        }

        dataTopDrawer.innerHTML += resultHtmlTopDrawer
    } else {
        dataContainer.innerHTML = ``
        let resultHtml = ``
        for (const property in apps) {
            const subPackage = property
            let html = ``
            apps[property].forEach((app) => {
                const isBookmark = app.bookmark
                    ? 'text-blue-500'
                    : 'text-gray-300'
                const status = capitalize(app.status ?? '')
                const isAdmin =
                    app.hidden == 'true'
                        ? '<i class="fa-duotone fa-eye text-blue-500"></i>'
                        : ''
                const statusHtml = status
                    ? `<span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-300">${status}</span>`
                    : ''
                const { package_rendered } = app
                html += `<li>
                                <div class='flex p-2 text-xs font-medium  text-gray-700 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white'>
                                <a href="${
                                    app.href
                                }" class="flex flex-1 items-center ">
                                    ${
                                        app.icon ??
                                        "<i class='fa-light fa-file'></i>"
                                    }
                                    <span class="flex-1 ml-3 whitespace-nowrap">${
                                        app.title
                                    }</span>
                                    ${isAdmin}
                                    ${statusHtml}
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-green-200 rounded dark:bg-gray-700 dark:text-gray-300">${package_rendered}</span>
                                    </a>
                                    <button tabIndex=-1 id='bookmark_${
                                        app.name
                                    }' onclick="bookmarkSearchModal('${
                    app.name
                }','${url}')" class='px-2 text-base ${isBookmark}'><i class="fa-solid fa-bookmark"></i></button>
                                </div>
                            </li>`
            })
            resultHtml += `<div >
                                <p class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">${subPackage}</p>
                                    <ul class="space-y-1">
                                        ${html}
                                    </ul>
                                </div>`
        }
        dataContainer.innerHTML += resultHtml
    }
}
function groupBySubPackage(arr) {
    const result = arr.reduce((group, product) => {
        const { sub_package_rendered } = product
        group[sub_package_rendered] = group[sub_package_rendered] ?? []
        group[sub_package_rendered].push(product)
        return group
    }, {})
    return result
}
function groupByFil(arr, filGroup) {
    const result = arr.reduce((group, product) => {
        const groupKey = product[filGroup]
        group[groupKey] = group[groupKey] ?? []
        group[groupKey].push(product)
        return group
    }, {})
    return result
}
function groupByFilHasSubFill(arr, tabGroup, filGroup, subFilGroup) {
    const result = arr.reduce((group, item) => {
        const groupTab = item[tabGroup]
        const groupKey = item[filGroup]
        const subGroupKey = item[subFilGroup]
        if (!group[groupTab]) {
            group[groupTab] = []
        }
        if (!group[groupTab][groupKey]) {
            group[groupTab][groupKey] = []
        }
        if (!group[groupTab][groupKey][subGroupKey]) {
            group[groupTab][groupKey][subGroupKey] = []
        }
        group[groupTab][groupKey][subGroupKey].push(item)
        return group
    }, {})
    return result
}
function capitalize(str) {
    const arr = str.split('_')
    for (var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1)
    }
    const str2 = arr.join(' ')
    return str2
}
function render(value, url) {
    apps = groupByFil(value, 'sub_package_rendered')
    renderHtml(apps, url)
}
function renderTopDrawer(value, url) {
    appsTopDrawer = groupByFilHasSubFill(
        value,
        'package_tab',
        'package_rendered',
        'sub_package_rendered'
    )
    renderHtml(appsTopDrawer, url, true)
}
function matchRegex(valueSearch, app) {
    const formatText = valueSearch.replaceAll(' ', '.*')
    var regex = new RegExp(formatText, 'i')
    const arr = [
        app.status,
        app.title,
        app.sub_package_rendered,
        // app.package_rendered,
        app.name,
        app.nickname,
    ]
    // console.log(arr)
    const str = arr.join(' ')
    return regex.test(str)
}
function filterAllAppCheckAdmin(arr) {
    return arr.filter((app) => {
        if (!currentUserIsAdmin) {
            return !app.hidden
        }
        return true
    })
}

function bookmarkSearchModal(entity, url) {
    $.ajax({
        type: 'put',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: { entity: entity },
        success: function (response) {
            if (response.success) {
                toastr.success(response.message, 'Bookmark')
                $(`[id="bookmark_${entity}"]`).toggleClass(
                    'text-blue-500 text-gray-300'
                )
                var data = response.meta[0]
                if (response.hits == 'add') {
                    console.log($('[id="list-bookmark"]'))
                    $('[id="list-bookmark"]').append(
                        `<a id="${data['name']}" href="${data['href']}" title="${data['title']}" 
                        class="p-1 border border-gray-200 text-left 1w-40 rounded text-blue-500 hover:text-gray-200 hover:bg-blue-500 block toggle-bookmark">
                        ${data['title']} 
                        </a>`
                    )
                    if (allApps) {
                        allApps.forEach((app) => {
                            if (app['name'] === entity) {
                                app['bookmark'] = true
                            }
                        })
                    }
                    if (allAppsTopDrawer) {
                        allAppsTopDrawer.forEach((app) => {
                            if (app['name'] === entity) {
                                app['bookmark'] = true
                            }
                        })
                    }
                } else {
                    $(`#${data['name']}`).remove()
                    if (allApps) {
                        allApps.forEach((app) => {
                            if (app['name'] === entity) {
                                app['bookmark'] = false
                            }
                        })
                    }
                    if (allAppsTopDrawer) {
                        allAppsTopDrawer.forEach((app) => {
                            if (app['name'] === entity) {
                                app['bookmark'] = false
                            }
                        })
                    }
                }
            }
        },
        error: function (response) {
            console.log(response)
        },
    })
}
