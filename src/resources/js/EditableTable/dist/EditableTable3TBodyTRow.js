"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TbodyTr = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const EditableTable3TBodyTDEmpty_1 = require("./EditableTable3TBodyTDEmpty");
class TbodyTr {
    constructor(params, 
    // private row: TableDataLine,
    rowIndex) {
        this.params = params;
        this.rowIndex = rowIndex;
        this.renderRow = (tableName, columns) => {
            const firstFixedRightIndex = columns.findIndex((column) => column.fixed === 'right');
            return columns
                .map((column, cix) => (0, EditableTable3TBodyTDEmpty_1.makeEmptyTd)(tableName, column, this.rowIndex, cix, firstFixedRightIndex))
                .join('');
        };
    }
    render() {
        const { tableName } = this.params;
        const dataSource = tableData[tableName];
        const dataLine = dataSource.data[this.rowIndex];
        const bg0 = dataLine['DESTROY_THIS_LINE'] ? 'bg-red-600' : '';
        const bg1 = dataLine['NEW_INSERTED_LINE'] ? 'bg-green-600' : '';
        const tr = document.createElement('tr');
        tr.id = `${tableName}__${this.rowIndex}`;
        tr.className = (0, tailwind_merge_1.twMerge)('__xyz__', bg0, bg1);
        tr.innerHTML = this.renderRow(tableName, tableColumns[tableName]);
        return tr;
    }
}
exports.TbodyTr = TbodyTr;
//# sourceMappingURL=EditableTable3TBodyTRow.js.map