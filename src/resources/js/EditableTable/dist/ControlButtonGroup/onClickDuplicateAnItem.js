"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.onDuplicateAnItem = void 0;
// declare let tableColumns: { [tableName: string]: TableColumn[] }
const onDuplicateAnItem = (params, rowIndex) => {
    const dataSource = tableData[params.tableName];
    // const columns = tableColumns[params.tableName]
    const dataLine = dataSource.data[rowIndex];
    dataSource.data.push(Object.assign(Object.assign({}, dataLine), { NEW_INSERTED_LINE: true }));
};
exports.onDuplicateAnItem = onDuplicateAnItem;
//# sourceMappingURL=onClickDuplicateAnItem.js.map