import { TableCell } from "./TableCell";

export const TableRows = (params) => {
    const { columns, dataSource, settings } = params
    const x = dataSource.map((line) => {
        const { trClassList } = settings
        const y = columns.map((column) => {
            const { dataIndex } = column
            const cell = TableCell(params, line[dataIndex], line, column)
            return cell
        });
        console.log(y)
        return `<tr class="${trClassList}">${y.join('')}</tr>`
    })

    // console.log(x)
    return `${x.join('')}`

}