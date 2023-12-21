import { looseInclude } from "./functions"

const addEventListenerForLi = (liId, id, params, onClickFn) => $(`#${liId}`).click((e) => onClickFn(e))

const editableListRender = (params, keyword = null) => {
    const {
        id,
        maxHeight = 400, dataSource, selected,
        // itemRenderer = (item, dataIndex) => console.log(item),
        itemRenderer = (item, dataIndex) => `<span id="${dataIndex}" class="px-2 py-1 block w-full">${item.name}</span>`,
        compareFn = (item, keyword) => item.name.includes(keyword),
        selectedClass = `bg-blue-800 text-white`,
        onClick = (e) => {
            console.log(`Selected ${e.target.id}`)
        },
    } = params


    const lis = Object.keys(dataSource).map((dataIndex) => {
        const item = dataSource[dataIndex]
        if (keyword) {
            if (!compareFn(item, keyword)) return
            //do not combine if clauses
        }
        const rendered = itemRenderer(item, dataIndex)
        const selectedArr = Array.isArray(selected) ? selected : [selected]
        const selectedStr = looseInclude(selectedArr, dataIndex) ? selectedClass : ''
        // console.log(item, dataIndex, selectedStr, selectedArr)
        const liId = `li_of_${id}_${dataIndex}`
        $(document).ready(() => addEventListenerForLi(liId, id, params, onClick))

        return `<li id="${liId}" class="hover:bg-gray-300 cursor-pointer block ${selectedStr}">${rendered}</li>`
    })

    const ul = `<ul class="border overflow-y-auto w-full" style="max-height:${maxHeight}px;">
        ${lis.join("<hr/>")}
    </ul>`

    return ul
}

const addEventListenerForInput = (inputId, id, params) => {
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
    const ulDivId = `ul_div_${id}`
    const animation = `absolute invisible transition-opacity duration-500 ease-in-out opacity-0`
    const div = `<div id="${id}"
        class="border shadow-md rounded p-0 bg-gray-50 ${animation}" 
        style="width:${width}px;z-index:1001;"
        >
        ${filterInput}
        <div id="${ulDivId}">${ul}</div>
    </div >
    `
    $(document).ready(() => addEventListenerForInput(inputId, ulDivId, params))

    return `${div} `
}