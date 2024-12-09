"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeThead = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const EditableTable3DefaultValue_1 = require("./EditableTable3DefaultValue");
const EditableTable3FixedColumn_1 = require("./FixedColumn/EditableTable3FixedColumn");
const Functions_1 = require("./Functions");
const MasterCheckbox_1 = require("./Renderer/IdAction/MasterCheckbox");
const makeThead = ({ tableName, tableConfig }) => {
    const columns = tableColumns[tableName];
    const firstFixedRightIndex = (0, EditableTable3FixedColumn_1.getFirstFixedRightColumnIndex)(columns);
    let colspanSkipCounter = 0;
    const result = columns.map((column, index) => {
        if (colspanSkipCounter > 0) {
            colspanSkipCounter--;
            return '';
        }
        if (column.colspan)
            colspanSkipCounter = column.colspan - 1;
        const title = column.title !== undefined ? column.title : Functions_1.Str.toHeadline(column.dataIndex);
        let masterCb = ``;
        if (['checkbox', 'checkbox_for_line'].includes(column.renderer)) {
            masterCb = (0, MasterCheckbox_1.renderMasterCB)(tableName, column);
        }
        const tooltipStr = (0, EditableTable3DefaultValue_1.getTooltip)(column);
        const hiddenStr = column.invisible ? 'hidden' : '';
        const widthStyle = column.width ? `width: ${column.width}px;` : '';
        const fixedStr = (0, EditableTable3FixedColumn_1.getFixedStr)(column.fixed, index, 'th');
        const bgStr = `bg-gray-100`;
        const textStr = `text-xs text-xs-vw text-gray-700`;
        const borderL = index == firstFixedRightIndex ? 'border-l' : '';
        const borderStr = `border-b border-r border-t border-gray-300 ${borderL}`;
        const rotateThStr = tableConfig.rotate45Width ? 'rotated-title-left-th' : '';
        const rotateDivStr = tableConfig.rotate45Width
            ? `rotated-title-div-${tableConfig.rotate45Width} text-left`
            : ``;
        const rotate45Height = tableConfig.rotate45Height || (tableConfig.rotate45Width || 100) / 1.41421; // Math.sqrt(2) side of square
        const rotateThStyle = `height: ${rotate45Height}px;`;
        const rotateDivStyle = tableConfig.rotate45Width
            ? `width: ${tableConfig.rotate45Width}px;`
            : ``;
        const classList = (0, tailwind_merge_1.twMerge)(`first-header-${index} ${hiddenStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} ${rotateThStr} text-center px-1`);
        const styleThStr = `${widthStyle} ${rotateThStyle}`;
        const styleDivStr = `${rotateDivStyle}`;
        return `<th 
            class="${classList}" 
            style="${styleThStr}" 
            title="${tooltipStr}" 
            colspan="${column.colspan || ''}"
        >
            <div class="${rotateDivStr}" style="${styleDivStr}">
                <div class="">${title}</div>
                <div class="text-xs text-gray-500">${column.subTitle || ''}</div>
                ${masterCb}
            </div>
        </th>`;
    });
    return result.join('');
};
exports.makeThead = makeThead;
//# sourceMappingURL=EditableTable3THead.js.map