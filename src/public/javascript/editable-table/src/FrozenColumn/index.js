export const getFixedClass = (column, index, tag, tableId) => {
    // console.log(column.fixed, column.dataIndex, index)
    if (column.fixed == 'left') return `table-${tag}-fixed-left table-${tag}-fixed-left-${index}-${tableId}`
    if (column.fixed == 'right') return `table-${tag}-fixed-right table-${tag}-fixed-right-${index}-${tableId}`
    return ``
}
const getColumnWidth = (tableId, columnIndex) => {
    const table = document.getElementById(tableId);
    console.log(tableId, table)
    const rows = table.getElementsByTagName('tr');
    let maxWidth = 0;
    // if (columnIndex === 0) {
    //     // console.log(i,cellWidth)
    //     const cell = rows[0].getElementsByTagName('th')[columnIndex];
    //     const cellWidth = cell.getBoundingClientRect().width;
    //     return (cellWidth) //<< No. column
    // }
    for (let i = 0; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName('td')[columnIndex];
        if (cell) {
            const cellWidth = cell.getBoundingClientRect().width;
            console.log(i, cellWidth)
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
        console.log(`.table-td-fixed-left-${index}-${tableId}`)
        $(`.table-td-fixed-left-${index}-${tableId}`).css('left', left);
        $(`.table-th-fixed-left-${index}-${tableId}`).css('left', left);
        // console.log("Setting left for ",index,"value",left)
        const right = column['fixedRight'];
        $(`.table-td-fixed-right-${index}-${tableId}`).css('right', right);
        $(`.table-th-fixed-right-${index}-${tableId}`).css('right', right);
    })

    // const allColumns = tableObjectColumns

    // tableObjectIndexedColumns[tableId] = {}
    // for (let i = 0; i < allColumns.length; i++) {
    //     const column = allColumns[i];
    //     tableObjectIndexedColumns[tableId][column['dataIndex']] = column;
    // }
}