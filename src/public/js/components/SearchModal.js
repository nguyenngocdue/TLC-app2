let dataContainer = null
let searchInput = null
let allApps = {}
let currentUserIsAdmin = null
let apps = []

const renderHtml = (apps) => {
    dataContainer.innerHTML = ``
    let resultHtml = ``
    for (const property in apps) {
        const subPackage = capitalize(property)
        let html = ``
        apps[property].forEach((app) => {
            const status = capitalize(app.status ?? '')
            const package = capitalize(app.package)
            html += `<li>
                        <a href="${app.href}" class="flex items-center p-2 text-xs font-medium text-gray-700 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <i class="fa-thin fa-arrow-right"></i>
                            <span class="flex-1 ml-3 whitespace-nowrap">${app.title}</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-red-200 rounded dark:bg-gray-700 dark:text-gray-400">${status}</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-normal text-gray-600 bg-green-200 rounded dark:bg-gray-700 dark:text-gray-400">${package}</span>
                        </a>
                    </li>`
        })
        resultHtml += `<div>
                        <p class="py-2 text-sm font-medium text-gray-900 dark:text-gray-400">${subPackage}</p>
                            <ul class="space-y-1">
                                ${html}
                            </ul>
                        </div>`
    }
    dataContainer.innerHTML += resultHtml
}
function groupBySubPackage(arr) {
    const result = arr.reduce((group, product) => {
        const { sub_package } = product
        group[sub_package] = group[sub_package] ?? []
        group[sub_package].push(product)
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
function render(value) {
    console.log(value)
    apps = groupBySubPackage(value)
    renderHtml(apps)
}
function matchRegex(valueSearch, app) {
    const formatText = valueSearch.replaceAll(' ', '.*')
    var regex = new RegExp(formatText, 'i')
    const arr = [
        app.title,
        app.sub_package,
        app.package,
        app.name,
        app.nickname,
    ]
    const str = arr.join(' ')
    return regex.test(str)
}
function filterAllAppCheckAdmin(arr) {
    return allApps.filter((app) => {
        if (!currentUserIsAdmin) {
            return !app.hidden
        }
        return true
    })
}
