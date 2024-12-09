"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getFixedStr = exports.getFirstFixedRightColumnIndex = exports.applyFixedColumnWidth = exports.applyTopFor2ndHeader = void 0;
const getFirstRowHeight = (tableId) => {
    const table = document.getElementById(tableId);
    if (!table)
        return 0;
    const rows = table.getElementsByTagName('tr');
    const cell = rows[0].getElementsByTagName('th')[0];
    if (!cell)
        return 0;
    const cellHeight = cell.getBoundingClientRect().height;
    return cellHeight;
};
const applyTopFor2ndHeader = (tableName) => {
    const firstRowHeight = getFirstRowHeight(tableName);
    // console.log('firstRowHeight', firstRowHeight)
    document.querySelectorAll(`#${tableName} .second-header`).forEach((element) => {
        element.setAttribute('style', `top: ${firstRowHeight}px`);
    });
};
exports.applyTopFor2ndHeader = applyTopFor2ndHeader;
const getColumnWidth = (tableId, columnIndex) => {
    const table = document.getElementById(tableId);
    if (!table)
        return 0;
    const rows = table.getElementsByTagName('tr');
    let maxWidth = 0;
    if (columnIndex === 0) {
        // console.log(i,cellWidth)
        const cell = rows[0].getElementsByTagName('th')[columnIndex];
        const cellWidth = cell.getBoundingClientRect().width;
        return cellWidth; //<< No. column
    }
    for (let i = 0; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName('td')[columnIndex];
        if (cell) {
            const cellWidth = cell.getBoundingClientRect().width;
            maxWidth = Math.max(maxWidth, cellWidth);
        }
    }
    return maxWidth;
};
const applyFixedColumnWidth = (tableName, columns) => {
    // console.log('applyFixedColumnWidth for', tableName)
    const arrayOfColumns = [];
    const cache = [];
    columns.forEach((_, index) => (cache[index] = getColumnWidth(tableName, index)));
    // console.log('cache', cache)
    let accumulated = 0;
    columns.forEach((column, index) => {
        column['fixedLeft'] = accumulated;
        accumulated += cache[index];
        arrayOfColumns.push(column);
    });
    arrayOfColumns.forEach((column, index) => {
        accumulated -= cache[index];
        column['fixedRight'] = accumulated;
    });
    // console.log('accumulated', arrayOfColumns)
    arrayOfColumns.forEach((_, index) => {
        document.querySelectorAll(`#${tableName} .first-header-${index}`).forEach((element) => {
            element.setAttribute('data-true-width-after-load', `${cache[index].toFixed(2)}px`);
        });
    });
    arrayOfColumns.forEach((column, index) => {
        const { fixedLeft, fixed } = column;
        const shortFixed = getShortFixed(fixed);
        if (shortFixed && shortFixed == 'left') {
            document
                .querySelectorAll(`#${tableName} .table-td-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                element.setAttribute('style', `left: ${fixedLeft}px`);
            });
            document
                .querySelectorAll(`#${tableName} .table-th-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                element.setAttribute('style', `left: ${fixedLeft}px`);
            });
        }
    });
    arrayOfColumns.forEach((column, index) => {
        const { fixedRight, fixed } = column;
        const shortFixed = getShortFixed(fixed);
        if (shortFixed && shortFixed === 'right') {
            document
                .querySelectorAll(`#${tableName} .table-td-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                element.setAttribute('style', `right: ${fixedRight}px`);
            });
            document
                .querySelectorAll(`#${tableName} .table-th-fixed-${shortFixed}-${index}`)
                .forEach((element) => {
                element.setAttribute('style', `right: ${fixedRight}px`);
            });
        }
    });
    // const allColumns = tableObjectColumns[tableName]
    // tableObjectIndexedColumns[tableName] = {}
    // for (let i = 0; i < allColumns.length; i++) {
    //     const column = allColumns[i]
    //     tableObjectIndexedColumns[tableName][column['dataIndex']] = column
    // }
};
exports.applyFixedColumnWidth = applyFixedColumnWidth;
const getFirstFixedRightColumnIndex = (columns) => columns.findIndex((column) => ['right', 'right-no-bg'].includes(column.fixed + ''));
exports.getFirstFixedRightColumnIndex = getFirstFixedRightColumnIndex;
const getShortFixed = (fixed) => fixed === 'left-no-bg' ? 'left' : fixed === 'right-no-bg' ? 'right' : fixed;
const getFixedStr = (fixed, index, th_or_td) => {
    const shortFixed = getShortFixed(fixed);
    if (shortFixed)
        return `table-${th_or_td}-fixed-${fixed} table-${th_or_td}-fixed-${shortFixed}-${index}`;
    return '';
};
exports.getFixedStr = getFixedStr;
//# sourceMappingURL=EditableTable3FixedColumn.js.map