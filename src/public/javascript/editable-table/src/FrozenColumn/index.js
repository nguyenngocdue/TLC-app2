export const getFixedClass = (column, index, tag) => {
    // console.log(column.fixed, column.dataIndex, index)

    if (column.fixed == 'left') return `table-${tag}-fixed-left table-${tag}-fixed-left-${index}`
    if (column.fixed == 'right') return `table-${tag}-fixed-right table-${tag}-fixed-right-${index}`
    return ``
}
const getColumnWidth = (tableId, columnIndex) => {
    const table = document.getElementById(tableId);
    // console.log(tableId, table)
    const rows = table.getElementsByTagName('tr');

    let maxWidth = 0;

    if (columnIndex === 0) {
        // console.log(i,cellWidth)
        const cell = rows[0].getElementsByTagName('th')[columnIndex];
        const cellWidth = cell.getBoundingClientRect().width;
        return (cellWidth) //<< No. column
    }

    for (let i = 0; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName('td')[columnIndex];
        if (cell) {
            const cellWidth = cell.getBoundingClientRect().width;
            maxWidth = Math.max(maxWidth, cellWidth);
        }
    }
    // console.log(maxWidth)
    return maxWidth;
}

export const applyFixedColumnWidth = (tableId, columns) => {
    const tableObjectColumns = []
    let accumulated = 0
    columns.forEach((column, index) => {
        column['fixedLeft'] = accumulated
        accumulated += getColumnWidth(tableId, index)
        tableObjectColumns.push(column)
    })
    // console.log(tableObjectColumns)
    // const totalWidth = accumulated
    tableObjectColumns.forEach((column, index) => {
        accumulated -= getColumnWidth(tableId, index)
        column['fixedRight'] = accumulated
    })

    // console.log(tableObjectColumns)

    tableObjectColumns.forEach((column, index) => {
        const left = column['fixedLeft'];
        $(`.table-td-fixed-left-${index}`).css('left', left);
        $(`.table-th-fixed-left-${index}`).css('left', left);
        // console.log("Setting left for ",index,"value",left)
        const right = column['fixedRight'];
        $(`.table-td-fixed-right-${index}`).css('right', right);
        $(`.table-th-fixed-right-${index}`).css('right', right);
    })

    // const allColumns = tableObjectColumns

    // tableObjectIndexedColumns[tableId] = {}
    // for (let i = 0; i < allColumns.length; i++) {
    //     const column = allColumns[i];
    //     tableObjectIndexedColumns[tableId][column['dataIndex']] = column;
    // }
}