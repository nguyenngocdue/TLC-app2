"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.reValueOrderNoColumn = void 0;
const reValueOrderNoColumn = (params) => {
    const { tableName } = params;
    const dataSource = tableData[tableName];
    if (dataSource && dataSource.data) {
        dataSource.data.forEach((dataLine, index) => {
            dataLine.order_no = (index + 1);
            // table11__order_no__text__14
            $(`#${tableName}__order_no__text__${index}`).val(index + 1);
        });
    }
};
exports.reValueOrderNoColumn = reValueOrderNoColumn;
//# sourceMappingURL=EditableTable3OrderableColumn.js.map