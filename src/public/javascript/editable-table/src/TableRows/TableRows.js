import { TableCell } from "./TableCell";

export const TableRows = (params) => {
    const { columns, dataSource, settings } = params
    const x = dataSource.map((line) => {
        const { tbody_tr } = settings.cssClass
        const y = columns.map((column, index) => {
            const { dataIndex } = column
            const cell = TableCell(params, line[dataIndex], line, column, index)
            return cell
        });
        return `<tr class="${tbody_tr}">${y.join('')}</tr>`
    })

    // console.log(x)
    return `${x.join('')}`

}