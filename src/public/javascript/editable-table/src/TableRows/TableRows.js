import { TableCell } from "./TableCell";

export const TableRows = (params) => {
    const { columns, dataSource, settings } = params
    const x = dataSource.map((line) => {
        const { tbody_tr } = settings.cssClass
        const y = Object.keys(columns).map((dataIndex, index) => {
            const column = columns[dataIndex]
            // const { dataIndex } = columns[dataIndex]
            const cell = TableCell(params, line[dataIndex], line, column, index)
            return cell
        });
        return `<tr class="${tbody_tr}" style="height: 52px;">${y.join('')}</tr>`
    })

    // console.log(x)
    return `${x.join('')}`

}