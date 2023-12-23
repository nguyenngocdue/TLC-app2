import { getFixedClass } from "./FrozenColumn"

export const TableHeader = (params) => {
    const { columns, settings, tableParams } = params
    const { tableId } = tableParams
    const { thead_tr } = settings.cssClass
    const ths = []
    // for (let i = 0; i < columns.length; i++) {
    Object.keys(columns).map((dataIndex, i) => {
        const column = columns[dataIndex]
        const { title, hidden, width = 100, renderer, control } = column
        if (hidden) return
        const fixedClass = getFixedClass(column, i, 'th', tableId)
        const styleStr = `style="width:${width}px"`
        const tooltip = []
        tooltip.push(`dataIndex: ${dataIndex}`)
        if (renderer) tooltip.push(`renderer: ${renderer}`)
        if (control) tooltip.push(`control: ${control}`)
        ths.push(`<th 
            class="border ${fixedClass}" 
            title="${tooltip.join("\n")}"
            ${styleStr}
            >${title}</th>`)
    })
    return `<thead class="sticky z-10 top-0">
        <tr class="${thead_tr}" title="">
            ${ths.join('')}
        </tr>
    </thead>`
}