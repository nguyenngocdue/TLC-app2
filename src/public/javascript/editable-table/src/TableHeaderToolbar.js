import { ETRAction } from "./Renderers/ETRAction"
import { getFixedClass } from "./FrozenColumn"

export const TableHeaderToolbar = (params) => {
    const { columns, settings, headerToolbar, tableParams } = params
    const { tableId } = tableParams
    // console.log(params)
    const { trClassList } = settings
    const ths = []
    // for (let i = 0; i < columns.length; i++) {
    Object.keys(columns).map((dataIndex, i) => {
        const column = columns[dataIndex]
        const { hidden, width = 100 } = column
        if (hidden) return
        let content = (headerToolbar[dataIndex]) || ''
        if (typeof content === 'object') {
            const { renderer, action } = content
            if (renderer === 'action') {
                content = ETRAction(action)
            }
        }

        const fixedClass = getFixedClass(column, i, "th", tableId)
        const styleStr = `style="width:${width}px"`
        ths.push(`<th class="border bg-gray-100 ${fixedClass}" ${styleStr}>${content}</th>`)

    })
    return `<thead class="sticky z-10 top-0">
        <tr class="${trClassList}">
            ${ths.join('')}
        </tr>
    </thead>`
}