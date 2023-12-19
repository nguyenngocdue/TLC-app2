export const TableHeaderSub = (params) => {
    const { columns, settings } = params
    const { trClassList } = settings
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title } = columns[i]
        ths.push(`<th title="${dataIndex}">${title}</th>`)
    }
    return `<thead class="sticky z-10 top-0">
        <tr class="${trClassList}">
            ${ths.join('')}
        </tr>
    </thead>`
}