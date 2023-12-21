import { getFixedClass } from "./FrozenColumn"

export const TableHeader = (params) => {
    const { columns, settings, tableParams } = params
    const { tableId } = tableParams
    const { thead_tr } = settings.cssClass
    const ths = []
    // for (let i = 0; i < columns.length; i++) {
    Object.keys(columns).map((dataIndex, i) => {
        const { title, hidden, width = 100 } = columns[dataIndex]
        if (hidden) return
        const fixedClass = getFixedClass(columns[dataIndex], i, 'th', tableId)
        const styleStr = `style="width:${width}px"`
        ths.push(`<th 
            class="border ${fixedClass}" 
            title="${dataIndex}"
            ${styleStr}
            >${title}</th>`)
    })
    return `<thead class="sticky z-10 top-0">
        <tr class="${thead_tr}">
            ${ths.join('')}
        </tr>
    </thead>`
}