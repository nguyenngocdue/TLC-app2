export const EditableList = (params) => {
    const {
        dataSource,
        itemRenderer = (item) => `<span class="p-2 block w-full">${item.name}</span>`,
        width = 200,
        maxHeight = 400,
        allowFilter = false,
    } = params

    const lis = Object.keys(dataSource).map((dataIndex) => {
        const rendered = itemRenderer(dataSource[dataIndex])
        return `<li class="hover:bg-gray-300 cursor-pointer block">${rendered}</li>`
    })

    const ul = `<ul class="border overflow-y-auto w-full" style="max-height:${maxHeight}px;">
        ${lis.join("<hr/>")}
    </ul>`

    const filterInput = !allowFilter ? '' : `<div class="flex">
        <input class="hover:border-transparent focus:border-transparent focus:outline-none rounded p-2 w-full bg-white" style="" />
        <div class="border-l-1 pr-2 flex items-center bg-white">
            <i class="fa-regular fa-magnifying-glass"></i>
        </div>
    </div>
    `

    const div = `
    <div class="border shadow-md rounded p-0" style="width:${width}px;">
    ${filterInput}
    ${ul}
    </div>
    `

    return `${div}`
}