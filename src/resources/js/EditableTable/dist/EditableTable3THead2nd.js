"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeThead2nd = void 0;
const ValueObject4_1 = require("./Renderer/ValueObject/ValueObject4");
const EditableTable3FixedColumn_1 = require("./FixedColumn/EditableTable3FixedColumn");
const tailwind_merge_1 = require("tailwind-merge");
const makeThead2nd = (params) => {
    const columns = tableColumns[params.tableName];
    const { dataHeader } = params;
    if (!dataHeader)
        return '';
    // console.log('makeThead2nd', columns, dataHeader)
    let hasActualText = false;
    const firstFixedRightIndex = (0, EditableTable3FixedColumn_1.getFirstFixedRightColumnIndex)(columns);
    const result = columns.map((column, index) => {
        const tooltip = column.tooltip || '';
        const hiddenStr = column.invisible ? 'hidden' : '';
        const widthStyle = column.width ? `width: ${column.width}px;` : '';
        let sndHeader = '';
        let classStr = '';
        if (dataHeader[column.dataIndex]) {
            const cellValue = dataHeader[column.dataIndex];
            const rendererParams = {
                controlId: '2ndHeaderItem',
                cellValue,
                params,
                dataLine: dataHeader,
                column,
                rowIndex: 0,
            };
            const result = new ValueObject4_1.ValueObject4(rendererParams).render();
            sndHeader = result.rendered;
            classStr = result.tdClass || '';
        }
        const fixedStr = (0, EditableTable3FixedColumn_1.getFixedStr)(column.fixed, index, 'th');
        const bgStr = `bg-gray-100`;
        const textStr = `text-xs text-xs-vw text-gray-500`;
        const borderL = index == firstFixedRightIndex ? 'border-l' : '';
        const borderStr = `border-b border-r border-gray-300 ${borderL}`;
        const classList = (0, tailwind_merge_1.twMerge)(`${hiddenStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} ${classStr} text-center`);
        hasActualText || (hasActualText = !!sndHeader);
        return `<th class="${classList}" style="${widthStyle}" title="${tooltip}">
                ${sndHeader}                
            </th>`;
    });
    if (hasActualText)
        return result.join('');
    return '';
};
exports.makeThead2nd = makeThead2nd;
//# sourceMappingURL=EditableTable3THead2nd.js.map