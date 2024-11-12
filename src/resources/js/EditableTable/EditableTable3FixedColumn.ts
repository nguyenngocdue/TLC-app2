import { TableColumn } from './Type/EditableTable3ColumnType'

const getColumnWidth = (tableId: string, columnIndex: number) => {
    const table = document.getElementById(tableId)
    if (!table) return 0
    const rows = table.getElementsByTagName('tr')

    let maxWidth = 0

    if (columnIndex === 0) {
        // console.log(i,cellWidth)
        const cell = rows[0].getElementsByTagName('th')[columnIndex]
        const cellWidth = cell.getBoundingClientRect().width
        return cellWidth //<< No. column
    }

    for (let i = 0; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName('td')[columnIndex]
        if (cell) {
            const cellWidth = cell.getBoundingClientRect().width
            maxWidth = Math.max(maxWidth, cellWidth)
        }
    }

    return maxWidth
}

export const applyFixedColumnWidth = (tableName: string, columns: TableColumn[]) => {
    console.log('applyFixedColumnWidth for', tableName)
    const arrayOfColumns: TableColumn[] = []
    const cache: any[] = []

    columns.forEach((_, index) => (cache[index] = getColumnWidth(tableName, index)))
    console.log('cache', cache)

    let accumulated = 0
    columns.forEach((column, index) => {
        column['fixedLeft'] = accumulated
        accumulated += cache[index]
        arrayOfColumns.push(column)
    })
    arrayOfColumns.forEach((column, index) => {
        accumulated -= cache[index]
        column['fixedRight'] = accumulated
    })
    // console.log('accumulated', arrayOfColumns)

    arrayOfColumns.forEach((column, index) => {
        const left = column['fixedLeft']
        document
            .querySelectorAll(`#${tableName} .table-td-fixed-left-${index}`)
            .forEach((element) => {
                element.setAttribute('style', `left: ${left}px`)
            })
        document
            .querySelectorAll(`#${tableName} .table-th-fixed-left-${index}`)
            .forEach((element) => {
                element.setAttribute('style', `left: ${left}px`)
            })
    })

    arrayOfColumns.forEach((column, index) => {
        const right = column['fixedRight']
        document
            .querySelectorAll(`#${tableName} .table-td-fixed-right-${index}`)
            .forEach((element) => {
                element.setAttribute('style', `right: ${right}px`)
            })
        document
            .querySelectorAll(`#${tableName} .table-th-fixed-right-${index}`)
            .forEach((element) => {
                element.setAttribute('style', `right: ${right}px`)
            })
    })

    // const allColumns = tableObjectColumns[tableName]

    // tableObjectIndexedColumns[tableName] = {}
    // for (let i = 0; i < allColumns.length; i++) {
    //     const column = allColumns[i]
    //     tableObjectIndexedColumns[tableName][column['dataIndex']] = column
    // }
}
