import { DataSource } from "./DataSource"

export const TableHeader = (params) => {
    const { columns } = params
    const { trClassList } = DataSource.TableSettings
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