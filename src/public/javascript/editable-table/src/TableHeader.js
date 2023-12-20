import { getFixedClass } from "./FrozenColumn"

export const TableHeader = (params) => {
    const { columns, settings, tableParams } = params
    const { tableId } = tableParams
    const { thead_tr } = settings.cssClass
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title, hidden, width = 100 } = columns[i]
        if (hidden) continue
        const fixedClass = getFixedClass(columns[i], i, 'th', tableId)
        const styleStr = `style="width:${width}px"`
        ths.push(`<th 
            class="border ${fixedClass}" 
            title="${dataIndex}"
            ${styleStr}
            >${title}</th>`)
    }
    return `<thead class="sticky z-10 top-0">
        <tr class="${thead_tr}">
            ${ths.join('')}
        </tr>
    </thead>`
}