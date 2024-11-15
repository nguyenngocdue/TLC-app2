import { TableColumn } from './Type/EditableTable3ColumnType'

const getFirstRowHeight = (tableId: string) => {
    const table = document.getElementById(tableId)
    if (!table) return 0
    const rows = table.getElementsByTagName('tr')
    const cell = rows[0].getElementsByTagName('th')[0]
    if (!cell) return 0
    const cellHeight = cell.getBoundingClientRect().height
    return cellHeight
}

export const applyTopFor2ndHeader = (tableName: string) => {
    const firstRowHeight = getFirstRowHeight(tableName)
    // console.log('firstRowHeight', firstRowHeight)
    document.querySelectorAll(`#${tableName} .second-header`).forEach((element) => {
        element.setAttribute('style', `top: ${firstRowHeight}px`)
    })
}

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
    // console.log('applyFixedColumnWidth for', tableName)
    const arrayOfColumns: TableColumn[] = []
    const cache: any[] = []

    columns.forEach((_, index) => (cache[index] = getColumnWidth(tableName, index)))
    // console.log('cache', cache)

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
    arrayOfColumns.forEach((_, index) => {
        document.querySelectorAll(`#${tableName} .first-header-${index}`).forEach((element) => {
            element.setAttribute('data-true-width-after-load', `${cache[index].toFixed(2)}px`)
        })
    })

    arrayOfColumns.forEach((column, index) => {
        const { fixedLeft, fixed } = column
        const shortFixed = getShortFixed(fixed)

        if (shortFixed && shortFixed == 'left') {
            document
                .querySelectorAll(`#${tableName} .table-td-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                    element.setAttribute('style', `left: ${fixedLeft}px`)
                })
            document
                .querySelectorAll(`#${tableName} .table-th-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                    element.setAttribute('style', `left: ${fixedLeft}px`)
                })
        }
    })

    arrayOfColumns.forEach((column, index) => {
        const { fixedRight, fixed } = column
        const shortFixed = getShortFixed(fixed)

        if (shortFixed && shortFixed === 'right') {
            document
                .querySelectorAll(`#${tableName} .table-td-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                    element.setAttribute('style', `right: ${fixedRight}px`)
                })
            document
                .querySelectorAll(`#${tableName} .table-th-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                    element.setAttribute('style', `right: ${fixedRight}px`)
                })
        }
    })

    // const allColumns = tableObjectColumns[tableName]

    // tableObjectIndexedColumns[tableName] = {}
    // for (let i = 0; i < allColumns.length; i++) {
    //     const column = allColumns[i]
    //     tableObjectIndexedColumns[tableName][column['dataIndex']] = column
    // }
}

export const getFirstFixedRightColumnIndex = (columns: TableColumn[]) =>
    columns.findIndex((column) => ['right', 'right-no-bg'].includes(column.fixed + ''))

const getShortFixed = (fixed?: string) =>
    fixed === 'left-no-bg' ? 'left' : fixed === 'right-no-bg' ? 'right' : fixed

export const getFixedStr = (fixed?: string, index?: number, th_or_td?: 'th' | 'td') => {
    const shortFixed = getShortFixed(fixed)
    if (shortFixed)
        return `table-${th_or_td}-fixed-${fixed} table-${th_or_td}-fixed-${shortFixed}-${index}`
    return ''
}
