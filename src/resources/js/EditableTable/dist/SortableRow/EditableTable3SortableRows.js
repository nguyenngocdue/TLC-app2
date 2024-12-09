"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.applySortableRow = void 0;
const sortablejs_1 = __importDefault(require("sortablejs"));
const EditableTable3ParamType_1 = require("../Type/EditableTable3ParamType");
const updateVirtualTableVisibleRows_1 = require("../VirtualScrolling/updateVirtualTableVisibleRows");
const EditableTable3ApplyRenderedTRow_1 = require("../EditableTable3ApplyRenderedTRow");
const EditableTable3OrderableColumn_1 = require("./EditableTable3OrderableColumn");
const shiftDataLine = (dataSource, oldIndex, newIndex) => {
    const [movedItem] = dataSource.data.splice(oldIndex, 1);
    dataSource.data.splice(newIndex, 0, movedItem);
    console.log(`Row moved from ${oldIndex + 1} to ${newIndex + 1}`);
};
const reRenderRows = (dataSource, tableParams, startIdx, endIdx) => {
    const { tableName } = tableParams;
    if (startIdx > endIdx) {
        //swap startIdx and endIdx
        const temp = startIdx;
        startIdx = endIdx;
        endIdx = temp;
    }
    const offsetStart = updateVirtualTableVisibleRows_1.lastRenderedStartIdx[tableName];
    // console.log('reRenderRows start', startIdx + 1, 'end', endIdx + 1, 'offsetStart', offsetStart)
    for (let i = startIdx; i <= endIdx; i++) {
        const emptyRow = (0, updateVirtualTableVisibleRows_1.renderOneEmptyRow)(tableParams, i + offsetStart, EditableTable3ParamType_1.Caller.APPLY_SORTABLE_ROW);
        if (!emptyRow)
            continue;
        // console.log(`Re-rendering row ${i}`, emptyRow, i)
        //replace the actual row with the empty row
        //skip first tr as it is spacer top: tr:nth-child is 1-based not 0-based:
        const selector = `#${tableName} tbody tr:nth-child(${i + 2})`;
        // console.log('selector', selector)
        const tr = document.querySelector(selector);
        // console.log('tr', tr)
        if (tr) {
            tr.replaceWith(emptyRow);
            const dataLine = dataSource.data[i + offsetStart];
            // console.log('replacing row', tr, 'with', emptyRow, 'dataLine', dataLine)
            (0, EditableTable3ApplyRenderedTRow_1.applyRenderedTRow)(tableParams, dataLine, i + offsetStart);
        }
    }
};
const applySortableRow = (params) => {
    const { tableName } = params;
    const tableBody = document.querySelector(`#${tableName} tbody`);
    sortablejs_1.default.create(tableBody, {
        animation: 150,
        handle: '.drag-handle', // Use the drag handle for sorting
        onEnd: (evt) => {
            // Ignore if the indices are unchanged
            if (evt.oldIndex === evt.newIndex ||
                evt.oldIndex === undefined ||
                evt.newIndex === undefined) {
                return;
            }
            const offsetStart = updateVirtualTableVisibleRows_1.lastRenderedStartIdx[tableName];
            const dataSource = tableData[tableName];
            const oldIndex = evt.oldIndex - 1; // Adjust for 1-based indexing if necessary
            const newIndex = evt.newIndex - 1; // Adjust for 1-based indexing if necessary
            if (dataSource && dataSource.data) {
                // Remove the element from the old index and insert it at the new index
                shiftDataLine(dataSource, oldIndex + offsetStart, newIndex + offsetStart);
                reRenderRows(dataSource, params, oldIndex, newIndex);
                (0, EditableTable3OrderableColumn_1.reValueOrderNoColumn)(params);
            }
            else {
                console.error('Data source is not valid.');
            }
        },
    });
};
exports.applySortableRow = applySortableRow;
//# sourceMappingURL=EditableTable3SortableRows.js.map