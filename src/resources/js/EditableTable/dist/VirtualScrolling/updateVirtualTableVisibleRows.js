"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.updateVisibleRows = exports.renderRows = exports.removeOneRow = exports.renderOneEmptyRow = exports.visibleRowIds = exports.lastRenderedStartIdx = void 0;
const EditableTable3ApplyRenderedTRow_1 = require("../EditableTable3ApplyRenderedTRow");
const EditableTable3TBodyTRow_1 = require("../EditableTable3TBodyTRow");
const EditableTable3ParamType_1 = require("../Type/EditableTable3ParamType");
const EditableTable3FixedColumn_1 = require("../FixedColumn/EditableTable3FixedColumn");
const EditableTable3SortableRows_1 = require("../SortableRow/EditableTable3SortableRows");
const EditableTable3OrderableColumn_1 = require("../SortableRow/EditableTable3OrderableColumn");
const TableManipulations_1 = require("../Functions/TableManipulations");
const bufferSize = 5;
exports.lastRenderedStartIdx = {};
const lastRenderedEndIdx = {};
exports.visibleRowIds = {}; // Use a Set to track currently visible row IDs
const updateSpacer = (id, height, hiddenRows, scrollTop) => {
    const spacer = document.querySelector(id);
    if (!spacer) {
        console.error(`${id} not found - virtual table failed to render`);
        return;
    }
    spacer.style.height = `${height}px`;
    spacer.setAttribute('data-hidden-rows', `${hiddenRows}`);
    spacer.setAttribute('data-scroll-top', `${scrollTop}`);
};
const calculateLoadRemoveIndices = (tableName, startIdx, endIdx, isScrollingDown) => {
    const indices = {
        toBeLoaded: { startIdx: 0, endIdx: 0 },
        toBeRemoved: { startIdx: 0, endIdx: 0 },
    };
    if (isScrollingDown) {
        if (exports.lastRenderedStartIdx[tableName] < startIdx) {
            indices.toBeRemoved.startIdx = exports.lastRenderedStartIdx[tableName];
            indices.toBeRemoved.endIdx = startIdx;
        }
        if (lastRenderedEndIdx[tableName] < endIdx) {
            indices.toBeLoaded.startIdx = lastRenderedEndIdx[tableName];
            indices.toBeLoaded.endIdx = endIdx;
        }
    }
    else {
        if (exports.lastRenderedStartIdx[tableName] > startIdx) {
            indices.toBeLoaded.startIdx = startIdx;
            indices.toBeLoaded.endIdx = exports.lastRenderedStartIdx[tableName];
        }
        if (lastRenderedEndIdx[tableName] > endIdx) {
            indices.toBeRemoved.startIdx = endIdx;
            indices.toBeRemoved.endIdx = lastRenderedEndIdx[tableName];
        }
    }
    return indices;
};
const renderOneEmptyRow = (tableParams, rowIdx, caller) => {
    const trId = `${tableParams.tableName}__${rowIdx}`;
    let needToCheckRendered = false;
    switch (caller) {
        case EditableTable3ParamType_1.Caller.ON_SCROLLING:
        case EditableTable3ParamType_1.Caller.ON_CLICK_ADD_AN_ITEM:
            needToCheckRendered = true;
            break;
        case EditableTable3ParamType_1.Caller.APPLY_SORTABLE_ROW:
        default:
            needToCheckRendered = false;
            break;
    }
    if (needToCheckRendered) {
        if (exports.visibleRowIds[tableParams.tableName].has(`${trId}`)) {
            // if (false)
            console.log('row already visible', trId, caller);
            return null;
        }
        exports.visibleRowIds[tableParams.tableName].add(`${trId}`);
    }
    // console.log(`renderOneEmptyRow ${rowIdx}`, caller)
    return new EditableTable3TBodyTRow_1.TbodyTr(tableParams, rowIdx).render();
};
exports.renderOneEmptyRow = renderOneEmptyRow;
const removeOneRow = (tableParams, rowIdx) => {
    const tableName = tableParams.tableName;
    const rowId = `${tableName}__${rowIdx}`;
    const hasInSet = exports.visibleRowIds[tableName].has(rowId);
    // Remove only if the row is currently visible
    if (hasInSet) {
        const row = document.querySelector(`#${rowId}`);
        if (row)
            row.remove();
        // console.log('row removed', rowId)
        exports.visibleRowIds[tableName].delete(rowId); // Remove the ID from the visibleRowIds Set
        // console.log('removing', rowId, 'to', visibleRowIds)
    }
};
exports.removeOneRow = removeOneRow;
const renderRows = (data, indices, tableParams, visibleRowCount, position) => {
    if (indices.startIdx == indices.endIdx)
        return;
    const tableName = tableParams.tableName;
    let slicedData = data.slice(indices.startIdx, indices.endIdx);
    // Adjust the sliced data based on scrolling direction and visibleRowCount
    if (slicedData.length > visibleRowCount) {
        if (position === 'bottom') {
            // Scrolling down, keep only the last visibleRowCount items
            slicedData = slicedData.slice(-visibleRowCount);
            indices.startIdx = indices.endIdx - visibleRowCount;
        }
        else {
            // Scrolling up, keep only the first visibleRowCount items
            slicedData = slicedData.slice(0, visibleRowCount);
            indices.endIdx = indices.startIdx + visibleRowCount;
        }
    }
    // Generate the rows using `renderOneRow`
    const renderedRows = slicedData
        .map((_, idx) => {
        const rowIndex = indices.startIdx + idx;
        const row = (0, exports.renderOneEmptyRow)(tableParams, rowIndex, EditableTable3ParamType_1.Caller.ON_SCROLLING);
        if (!row)
            return '';
        return row.outerHTML;
    })
        .join('');
    // Insert the rendered rows into the table
    // const spacerId = `#${tableName} tbody>tr#spacer-${position}`
    // position === 'bottom' ? $(spacerId).before(renderedRows) : $(spacerId).after(renderedRows)
    if (position === 'bottom') {
        (0, TableManipulations_1.addTrBeforeBtmSpacer)(tableName, renderedRows);
    }
    else {
        (0, TableManipulations_1.addTrAfterTopSpacer)(tableName, renderedRows);
    }
    // Ensure `applyRenderedTRow` is called after the rows are attached to the DOM
    slicedData.forEach((row, idx) => {
        const rowElement = document.getElementById(`${tableName}__${indices.startIdx + idx}`);
        if (rowElement) {
            (0, EditableTable3ApplyRenderedTRow_1.applyRenderedTRow)(tableParams, row, indices.startIdx + idx);
        }
    });
};
exports.renderRows = renderRows;
const removeRows = (tableParams, indices) => {
    if (indices.startIdx == indices.endIdx)
        return;
    for (let i = indices.startIdx; i < indices.endIdx; i++) {
        (0, exports.removeOneRow)(tableParams, i);
    }
};
const updateVisibleRows = (virtualTable, dataSource, tableParams, firstLoad = false) => {
    const { tableName, tableConfig } = tableParams;
    const columns = tableColumns[tableName];
    const viewportHeight = tableConfig.maxH || 640;
    const rowHeight = tableConfig.rowHeight || 45;
    const data = dataSource.data;
    const totalRows = data.length;
    const visibleRowCount = Math.ceil(viewportHeight / rowHeight) + bufferSize * 2;
    const tableContainer = virtualTable.parentElement;
    tableContainer.style.height = `${viewportHeight}px`;
    tableContainer.style.overflowY = 'auto';
    tableContainer.setAttribute('data-virtual-row-height', `${rowHeight}`);
    tableContainer.setAttribute('data-buffer-size', `${bufferSize}`);
    tableContainer.setAttribute('data-viewport-height', `${viewportHeight}`);
    tableContainer.setAttribute('data-visible-row-count', `${visibleRowCount}`);
    const scrollTop = tableContainer.scrollTop;
    // console.log('scrollTop', scrollTop, virtualTable)
    const startIdx = Math.max(0, Math.floor(scrollTop / rowHeight) - bufferSize);
    const endIdx = Math.min(totalRows, startIdx + visibleRowCount);
    // console.log('startIdx', startIdx, 'endIdx', endIdx)
    // if (startIdx === lastRenderedStartIdx && endIdx === lastRenderedEndIdx) {
    //     return // Skip if no changes
    // }
    // if (startIdx == lastRenderedStartIdx[tableName]) {
    // return
    // }
    const isScrollingDown = startIdx > exports.lastRenderedStartIdx[tableName];
    const indices = calculateLoadRemoveIndices(tableName, startIdx, endIdx, isScrollingDown);
    updateSpacer(`#${tableName} tbody>tr#spacer-top`, startIdx * rowHeight, startIdx, scrollTop);
    updateSpacer(`#${tableName} tbody>tr#spacer-bottom`, (totalRows - endIdx) * rowHeight, totalRows - endIdx, scrollTop);
    if (firstLoad) {
        const toBeLoaded = { startIdx: 0, endIdx: visibleRowCount };
        (0, exports.renderRows)(data, toBeLoaded, tableParams, visibleRowCount, 'bottom');
    }
    else {
        if (isScrollingDown) {
            (0, exports.renderRows)(data, indices.toBeLoaded, tableParams, visibleRowCount, 'bottom');
            removeRows(tableParams, indices.toBeRemoved);
        }
        else {
            (0, exports.renderRows)(data, indices.toBeLoaded, tableParams, visibleRowCount, 'top');
            removeRows(tableParams, indices.toBeRemoved);
        }
    }
    if (tableConfig.orderable) {
        (0, EditableTable3SortableRows_1.applySortableRow)(tableParams);
        (0, EditableTable3OrderableColumn_1.reValueOrderNoColumn)(tableParams);
    }
    (0, EditableTable3FixedColumn_1.applyFixedColumnWidth)(tableName, columns);
    exports.lastRenderedStartIdx[tableName] = startIdx;
    lastRenderedEndIdx[tableName] = endIdx;
};
exports.updateVisibleRows = updateVisibleRows;
//# sourceMappingURL=updateVirtualTableVisibleRows.js.map