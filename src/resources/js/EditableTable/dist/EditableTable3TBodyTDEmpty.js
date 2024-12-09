"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeEmptyTd = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const EditableTable3FixedColumn_1 = require("./FixedColumn/EditableTable3FixedColumn");
const makeEmptyTd = (tableName, column, rowIndex, columnIndex, firstFixedRightIndex) => {
    const hiddenStr = column.invisible ? 'hidden' : '';
    const alignStr = column.align ? `text-${column.align}` : '';
    const { dataIndex, renderer } = column;
    const fixedStr = (0, EditableTable3FixedColumn_1.getFixedStr)(column.fixed, columnIndex, 'td');
    const textStr = `text-sm text-sm-vw`;
    const borderL = columnIndex == firstFixedRightIndex ? 'border-l xxx' : '';
    const borderStr = `border-b border-r border-gray-300 ${borderL}`;
    const classList = (0, tailwind_merge_1.twMerge)(`${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${textStr}`);
    const widthStr = column.width ? `width: ${column.width}px;` : '';
    const styleList = `${widthStr}`;
    const controlId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`;
    return `<td id="${controlId}__td" class="${classList}" style="${styleList}" 
                data-row="${rowIndex}" 
                data-col="${columnIndex}"
                data-column-key="${dataIndex}"
                >
                <div id="${controlId}__div" class="min-h-5 fade-in"></div>
            </td>`;
};
exports.makeEmptyTd = makeEmptyTd;
//# sourceMappingURL=EditableTable3TBodyTDEmpty.js.map