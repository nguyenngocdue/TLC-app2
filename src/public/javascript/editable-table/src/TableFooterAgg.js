export const TableFooterAgg = (params) => {
    const { columns, settings } = params
    const { tfoot_tr } = settings.cssClass
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title, hidden } = columns[i]
        if (hidden) continue
        ths.push(`<th class="border" title="${dataIndex}">${title}</th>`)
    }
    return `<tfoot>
        <tr class="${tfoot_tr}">
            ${ths.join('')}
        </tr>
    </tfoot>`
}