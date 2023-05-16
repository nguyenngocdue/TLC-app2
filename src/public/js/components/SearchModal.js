let dataContainer = null
let searchInput = null
let allApps = {}
let currentUserIsAdmin = null
let apps = []

const renderHtml = (apps, url) => {
    dataContainer.innerHTML = ``
    let resultHtml = ``
    for (const property in apps) {
        const subPackage = property
        let html = ``
        apps[property].forEach((app) => {
            const isBookmark = app.bookmark ? 'text-blue-500' : 'text-gray-300'
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
                        <a href="${app.href}" class="flex flex-1 items-center ">
                            ${app.icon ?? "<i class='fa-light fa-file'></i>"}
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
        resultHtml += `<div>
                        <p class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">${subPackage}</p>
                            <ul class="space-y-1">
                                ${html}
                            </ul>
                        </div>`
    }
    dataContainer.innerHTML += resultHtml
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
function capitalize(str) {
    const arr = str.split('_')
    for (var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1)
    }
    const str2 = arr.join(' ')
    return str2
}
function render(value, url) {
    apps = groupBySubPackage(value)
    renderHtml(apps, url)
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
                    $('#list-bookmark').append(
                        `<a id="${data['name']}" href="${data['href']}" title="${data['title']}" class="p-1 border border-gray-200 text-left w-40 rounded text-blue-500 hover:text-gray-200 hover:bg-blue-500 toogle-bookmark hidden">
                        ${data['title']} 
                        </a>`
                    )
                } else {
                    $(`#${data['name']}`).remove()
                }
            } else {
                toastr.warning(response.message, 'Bookmark')
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {},
    })
}
