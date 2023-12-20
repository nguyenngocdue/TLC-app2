import { ETAction } from "./Controls/ETAction"

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

        ths.push(`<th class="border bg-gray-100">${content}</th>`)

    }
    return `<thead class="sticky z-10 top-0">
        <tr class="${trClassList}">
            ${ths.join('')}
        </tr>
    </thead>`
}