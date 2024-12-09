"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.applyRenderedTbody = void 0;
const EditableTable3ApplyRenderedTRow_1 = require("./EditableTable3ApplyRenderedTRow");
const applyRenderedTbody = (params) => {
    const dataSource = tableData[params.tableName];
    if (!dataSource.data)
        return '';
    dataSource.data.forEach((row, rowIndex) => {
        (0, EditableTable3ApplyRenderedTRow_1.applyRenderedTRow)(params, row, rowIndex);
    });
};
exports.applyRenderedTbody = applyRenderedTbody;
//# sourceMappingURL=EditableTable3ApplyRenderedTbody.js.map