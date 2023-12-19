import { DataSource } from "./DataSource"

export const TableFooter = (params) => {
    const { columns } = params
    const { trClassList } = DataSource.TableSettings
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title } = columns[i]
        ths.push(`<th title="${dataIndex}">${title}</th>`)
    }
    return `<tr class="${trClassList}">
        ${ths.join('')}
    </tr>`
}