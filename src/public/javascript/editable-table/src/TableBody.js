import { TableRows } from "./TableRows/TableRows"

export const TableBody = (params) => {
    const tableRows = TableRows(params)
    return `<tbody class='divide-y bg-white dark:divide-gray-700 dark:bg-gray-800'>
        ${tableRows}
    </tbody>`
}