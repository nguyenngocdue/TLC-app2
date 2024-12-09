"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.applyVirtualScrolling = void 0;
// import { tdOnMouseMove } from './tdOnMouseMove'
const updateVirtualTableVisibleRows_1 = require("./updateVirtualTableVisibleRows");
const applyVirtualScrolling = (params) => {
    const virtualTable = document.querySelector(`#${params.tableName} table`);
    // console.log('virtualTable', divId, virtualTable)
    if (virtualTable) {
        const dataSource = tableData[params.tableName];
        const tableContainer = virtualTable.parentElement;
        tableContainer.addEventListener('scroll', () => (0, updateVirtualTableVisibleRows_1.updateVisibleRows)(virtualTable, dataSource, params));
        // Initial render
        (0, updateVirtualTableVisibleRows_1.updateVisibleRows)(virtualTable, dataSource, params, true);
    }
};
exports.applyVirtualScrolling = applyVirtualScrolling;
//# sourceMappingURL=EditableTable3VirtualScrolling.js.map