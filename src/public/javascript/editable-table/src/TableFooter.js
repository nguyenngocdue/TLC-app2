export const TableFooter = (params) => {
    const { columns } = params
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title } = columns[i]
        ths.push(`<th title="${dataIndex}">${title}</th>`)
    }
    return `<tr>${ths.join('')}</tr>`
}