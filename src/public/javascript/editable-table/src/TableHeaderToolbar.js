import { ETAction } from "./Controls/ETAction"
import { getFixedClass } from "./FrozenColumn"

export const TableHeaderToolbar = (params) => {
    const { columns, settings, headerToolbar } = params
    // console.log(params)
    const { trClassList } = settings
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, hidden } = columns[i]
        if (hidden) continue
        let content = (headerToolbar[dataIndex]) || ''
        if (typeof content === 'object') {
            const { renderer, action } = content
            if (renderer === 'action') {
                content = ETAction(action)
            }
        }

        const fixedClass = getFixedClass(columns[i], i, "th")
        ths.push(`<th class="border bg-gray-100 ${fixedClass}">${content}</th>`)

    }
    return `<thead class="sticky z-10 top-0">
        <tr class="${trClassList}">
            ${ths.join('')}
        </tr>
    </thead>`
}