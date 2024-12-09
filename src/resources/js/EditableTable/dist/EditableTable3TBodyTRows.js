"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TbodyTrs = void 0;
const EditableTable3TBodyTRow_1 = require("./EditableTable3TBodyTRow");
class TbodyTrs {
    constructor(params) {
        this.params = params;
    }
    render() {
        // const { dataSource } = this.params
        const dataSource = tableData[this.params.tableName];
        if (!dataSource.data)
            return [];
        const result = dataSource.data.map((_, rowIndex) => new EditableTable3TBodyTRow_1.TbodyTr(this.params, rowIndex).render());
        return result;
    }
}
exports.TbodyTrs = TbodyTrs;
//# sourceMappingURL=EditableTable3TBodyTRows.js.map