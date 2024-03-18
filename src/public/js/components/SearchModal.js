let calendarContainer = null
let modalContainer = null
let dataContainer = null
let dataTopDrawer = null
let searchInput = null
let allApps = null
let allAppsRecent = null
let allAppsTopDrawer = null
let currentUserIsAdmin = null
let apps = []
let appsTopDrawer = []

const renderSearchModalHtml = (apps,url) => {
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
                                <a href="${app.href
                    }" class="flex flex-1 items-center ">
                                    ${app.icon ??
                    "<i class='fa-light fa-file'></i>"
                    }
                                    <span class="flex-1 ml-3 whitespace-nowrap">${app.title
                    }</span>
                                    ${isAdmin}
                                    ${statusHtml}
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-green-200 rounded dark:bg-gray-700 dark:text-gray-300">${package_rendered}</span>
                                    </a>
                                    <button tabIndex=-1 id='bookmark_${app.name
                    }' onclick="bookmarkSearchModal('${app.name
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
const matchIconForAction = (action) => {
    switch (action) {
        case 'index':
            icon = '<i class="fa-solid fa-table-cells"></i>';
            break;
        case 'show':
            icon = '<i class="fa-duotone fa-print"></i>';
            break;
        case 'edit':
            icon = '<i class="fa-duotone fa-pen-to-square"></i>'
            break;
        default:
            icon = '<i class="fa-solid fa-question"></i>'
            break;
    }
    return icon;

}
const renderTopDrawerHtml = (buttonTabs,recentDoc, appsRender, url) => {
        dataTopDrawer.innerHTML = ``;
        let htmlButtons = ``;
        let indexButtonTabs = 1;
        buttonTabs.forEach((button) => {
            const nameButton = capitalize(button) + 's';
            const isActiveCss = indexButtonTabs == 1 ? 'active' : 'dark:hover:text-gray-300';
            htmlButtons += 
            `<button type="button" onmouseover="changeTab(event,${indexButtonTabs})" class="hs-tab-active:border-blue-500 hs-tab-active:text-blue-600 dark:hs-tab-active:text-blue-600 py-1 pr-4 inline-flex items-center gap-2 border-r-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 ${isActiveCss}" id="vertical-tab-with-border-item-${indexButtonTabs}" data-hs-tab="#vertical-tab-with-border-${indexButtonTabs}" aria-controls="vertical-tab-with-border-${indexButtonTabs}" role="tab">
             ${nameButton}
            </button>`
            indexButtonTabs++;
        })
        var htmlButtonTabs = `
        <div class="border-r border-gray-200 dark:border-gray-700">
            <nav class="flex flex-col space-y-2" aria-label="Tabs" role="tablist" data-hs-tabs-vertical="true">
            ${htmlButtons}
            </nav>
        </div>
        `
        let htmlProperty = ``;
        const lastIndexButtonRecent = buttonTabs.length;
        for (const property in recentDoc) {
            const subPackage = property
            let html = ``
            recentDoc[property].forEach((item) => {
                const entityIdHtml = item.entity_id
                            ? `<span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-300">${item.entity_id}</span>`
                            : ''
                const iconAction = matchIconForAction(item.action_recent);
                const actionHtml = item.action_recent ?  `<span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-green-200 rounded dark:bg-gray-700 dark:text-gray-300">${iconAction}</span>`
                : '';
                html += `<li>
                            <div class='flex p-2 text-xs font-medium  text-gray-600 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white'>
                                    <a href="${item.href_recent}" class="flex flex-1 px-2 items-center ">
                                    ${item.icon ??"<i class='fa-light fa-file'></i>"}
                                <span class="flex-1 ml-3 whitespace-nowrap">${item.title}</span>
                                ${actionHtml}
                                ${entityIdHtml}
                                    </a>
                            </div>
                        </li>`;
            })
            htmlProperty += `<p id='${subPackage}-recent' onmouseover="visible('${subPackage}-recent')" class="p-2 text-sm font-medium text-gray-900 dark:text-gray-300">${subPackage}</p>
                                <ul id='ul-${subPackage}-recent' class="space-y-1 hidden">
                                    ${html}
                                </ul>`;
        }
        var htmlRecentDoc = `<div id="vertical-tab-with-border-${lastIndexButtonRecent}" class="hidden" role="tabpanel" aria-labelledby="vertical-tab-with-border-item-${lastIndexButtonRecent}">
                                ${htmlProperty}
                            </div>`
        let resultHtmlTopDrawer = ``
        let indexTabs = 1;
        for (const app_index in appsRender) {
            let resultHtml = ``
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
                    apps[property][value].forEach((app,index) => {
                        console.log(index);
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
                                <button tabIndex=-1 id='bookmark_${app.name
                            }' onclick="bookmarkSearchModal('${app.name
                            }','${url}')" class='px-2 text-base ${isBookmark}'>
                        <i class="fa-solid fa-bookmark"></i></button>
                                <a href="${app.href
                            }" class="flex flex-1 px-2 items-center ">
                                ${app.icon ??
                            "<i class='fa-light fa-file'></i>"
                            }
                                        <span class="flex-1 ml-3 whitespace-nowrap">${app.title
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
                                    <p id='${package}-${sub_package}' onmouseover="visible('${package}-${sub_package}')" class="p-2 text-sm font-medium text-gray-900 dark:text-gray-300">${sub_package} ${totalHtml}</p>
                                        <ul id='ul-${package}-${sub_package}' class="space-y-1 hidden">
                                           ${htmlTopDrawer}
                                        </ul>
                            </li>`
                    const max = 2
                    if (lengthGroup >= max) {
                        const divisionPra = Math.ceil(lengthGroup / max)
                        if (count % max === 0) {
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
                        htmlGroup = `<ul class="grid grid-cols-1 md:grid-cols-2">
                                                ${htmlFlexCol}
                                    </ul>`
                        break
                    case 5:
                    default:
                        htmlGroup = `<ul class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                                                ${htmlFlexCol}
                                    </ul>`
                        break
                }
                resultHtml += `<div class='px-2'>
                                        <p class="p-2 text-sm font-medium text-gray-900 dark:text-gray-300">${package} ${totalPackageHtml}</p>
                                            ${htmlGroup}
                                </div>`
            }
            const isHiddenCss = indexTabs !== 1 ?  'hidden' : '';
            resultHtmlTopDrawer += `<div id="vertical-tab-with-border-${indexTabs}" class="${isHiddenCss}" role="tabpanel" aria-labelledby="vertical-tab-with-border-item-${indexTabs}">
                                        <div class="grid grid-rows-auto grid-flow-col">
                                            <div class='xl:flex flex-wrap'>${resultHtml}</div>
                                        </div>
                                    </div>
                                    `;
            indexTabs++;
        }
        dataTopDrawer.innerHTML += `<div class="flex flex-wra1p">
                                        ${htmlButtonTabs}
                                        <div class="ml-3 overflow-x-auto">
                                        ${resultHtmlTopDrawer} 
                                        ${htmlRecentDoc}
                                        </div>
                                    </div>`;
   
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
    renderSearchModalHtml(apps, url)
}
function renderTopDrawer(buttonTabs,recentDoc,value, url) {
    appsTopDrawer = groupByFilHasSubFill(
        value,
        'package_tab',
        'package_rendered',
        'sub_package_rendered'
    )
    recentDoc = groupByFil(recentDoc, 'sub_package_rendered');
    renderTopDrawerHtml(buttonTabs,recentDoc, appsTopDrawer, url)
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
function changeTab(evt, index) {
    const elements = document.querySelectorAll('div[id^="vertical-tab-with-border-"]');
    const elementButtons = document.querySelectorAll('button[id^="vertical-tab-with-border-item-"]');
    elements.forEach((element) => {
        if(!element.classList.contains('hidden')) {
            element.classList.add('hidden');
        }
    });
    elementButtons.forEach((elementButton) => {
        if(elementButton.classList.contains('active')) {
            elementButton.classList.remove('active');
        }
    });
    evt.target.classList.add("active")
    document.getElementById(`vertical-tab-with-border-${index}`).classList.remove("hidden")
  }
function visible(id){
    var ul = document.getElementById(`ul-${id}`);
    if(ul.classList.contains('hidden')){
        ul.classList.remove('hidden');
    }else{
        ul.classList.add('hidden');
    }
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
