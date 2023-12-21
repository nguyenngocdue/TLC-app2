const editableListRender = (params, keyword = null) => {
    const {
        maxHeight = 400, dataSource,
        itemRenderer = (item) => `<span class="p-2 block w-full">${item.name}</span>`,
        compareFn = (item, keyword) => item.name.includes(keyword)
    } = params

    const lis = Object.keys(dataSource).map((dataIndex) => {
        const item = dataSource[dataIndex]
        if (keyword) {
            if (!compareFn(item, keyword)) return
        }
        const rendered = itemRenderer(item)
        return `<li class="hover:bg-gray-300 cursor-pointer block">${rendered}</li>`
    })

    const ul = `<ul class="border overflow-y-auto w-full" style="max-height:${maxHeight}px;">
        ${lis.join("<hr/>")}
    </ul>`

    return ul
}

const addEventListener = (inputId, id, params) => {
    $(`#${inputId}`).on('keyup', (e) => {
        const ul = editableListRender(params, e.target.value)
        // console.log(e.target.value, ul)
        $(`#${id}`).html(ul)
    })
}

export const EditableList = (params) => {
    const { name, id, width = 200, allowFilter = false, } = params
    const inputId = `input_of_${id}`
    const ul = editableListRender(params)

    const filterInput = !allowFilter ? '' : `<div class="flex">
        <input id="${inputId}" class="hover:border-transparent focus:border-transparent focus:outline-none rounded p-2 w-full bg-white" />
        <div class="border-l-1 pr-2 flex items-center bg-white">
            <i class="fa-regular fa-magnifying-glass"></i>
        </div>
    </div>
    `

    const div = `
    <div class="border shadow-md rounded p-0" style="width:${width}px;">
    ${filterInput}
        <div id="${id}">${ul}</div>
    </div>
    `
    $(document).ready(() => addEventListener(inputId, id, params))

    return `${div}`
}