"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.applyVirtualStatic = void 0;
const EditableTable3FixedColumn_1 = require("../FixedColumn/EditableTable3FixedColumn");
const EditableTable3OrderableColumn_1 = require("../SortableRow/EditableTable3OrderableColumn");
const EditableTable3SortableRows_1 = require("../SortableRow/EditableTable3SortableRows");
const updateVirtualTableVisibleRows_1 = require("./updateVirtualTableVisibleRows");
const applyVirtualStatic = (params) => {
    const { tableName, tableConfig } = params;
    const columns = tableColumns[tableName];
    const dataSource = tableData[tableName];
    const indices = { startIdx: 0, endIdx: dataSource.data.length };
    (0, updateVirtualTableVisibleRows_1.renderRows)(dataSource.data, indices, params, dataSource.data.length, 'bottom');
    if (tableConfig.orderable) {
        (0, EditableTable3SortableRows_1.applySortableRow)(params);
        (0, EditableTable3OrderableColumn_1.reValueOrderNoColumn)(params);
    }
    (0, EditableTable3FixedColumn_1.applyFixedColumnWidth)(tableName, columns);
};
exports.applyVirtualStatic = applyVirtualStatic;
//# sourceMappingURL=EditableTable3VirtualStatic.js.map