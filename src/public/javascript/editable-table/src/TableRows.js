import { TableCell } from "./TableCell";

export const TableRows = (params) => {
    const { columns, dataSource, settings } = params
    const x = dataSource.map((line) => {
        const { trClassList } = settings
        const y = columns.map((element) => {
            const { dataIndex } = element
            const cell = TableCell(params, line[dataIndex], line)
            return `<td>${cell}</td>`
        });
        return `<tr class="${trClassList}">${y.join('')}</tr>`
    })

    // console.log(x)
    return `<tbody class='divide-y bg-white dark:divide-gray-700 dark:bg-gray-800'>
        ${x.join('')}
    </tbody>`
}