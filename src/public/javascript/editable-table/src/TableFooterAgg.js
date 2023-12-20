export const TableFooterAgg = (params) => {
    const { columns, settings } = params
    const { tfoot_tr } = settings.cssClass
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title } = columns[i]
        ths.push(`<th title="${dataIndex}">${title}</th>`)
    }
    return `<tfoot>
        <tr class="${tfoot_tr}">
            ${ths.join('')}
        </tr>
    </tfoot>`
}