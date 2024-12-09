"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeTfoot = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const EditableTable3FixedColumn_1 = require("./FixedColumn/EditableTable3FixedColumn");
const makeTfoot = ({ tableName }) => {
    const columns = tableColumns[tableName];
    const firstFixedRightIndex = (0, EditableTable3FixedColumn_1.getFirstFixedRightColumnIndex)(columns);
    let hasActualText = false;
    const footers = columns.map((column, index) => {
        const hiddenStr = column.invisible ? 'hidden' : '';
        const alignStr = column.align ? `text-${column.align}` : '';
        const fixedStr = (0, EditableTable3FixedColumn_1.getFixedStr)(column.fixed, index, 'th');
        const textStr = `text-xs text-xs-vw`;
        const bgStr = `bg-gray-100`;
        const borderL = index == firstFixedRightIndex ? 'border-l' : '';
        const borderStr = `border-r border-t border-b border-gray-300 ${borderL}`;
        const classList = (0, tailwind_merge_1.twMerge)(`${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} p-1`);
        if (column.footer) {
            hasActualText || (hasActualText = !!column.footer);
            return `<th class="${classList}">${column.footer}</td>`;
        }
        else {
            return `<th class="${classList}"></td>`;
        }
    });
    if (!hasActualText)
        return '';
    // console.log('makeTfoot', footers)
    const footerStr = footers.join('');
    const classList = `sticky z-10 bg-gray-100`;
    const styleList = `bottom: -1px;`;
    return `<tr class="${classList}" style="${styleList}">${footerStr}</tr>`;
};
exports.makeTfoot = makeTfoot;
//# sourceMappingURL=EditableTable3TFoot.js.map