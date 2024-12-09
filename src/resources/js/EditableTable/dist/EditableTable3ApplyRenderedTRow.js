"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.applyRenderedTRow = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const EditableTable3TCell_1 = require("./EditableTable3TCell");
const applyRenderedTRow = (params, row, rowIndex) => {
    const { tableName, tableConfig } = params;
    const dataSource = tableData[tableName];
    if (!dataSource.data)
        return '';
    const columns = tableColumns[tableName];
    // console.log('applying rendered row', row, rowIndex)
    columns.forEach((column) => {
        const tCell = (0, EditableTable3TCell_1.makeTCell)(params, row, column, rowIndex);
        // console.log('making column for row', column, rowIndex, tCell)
        const { rendered, tdClass, tdStyle, tdTooltip, divClass, divStyle, divTooltip, p_2, componentCase, applyPostRenderScript, applyOnMouseMoveScript, applyOnChangeScript, } = tCell;
        const tdStyleString = Object.entries(tdStyle)
            .map(([key, value]) => `${key}: ${value};`)
            .join(' ');
        const divStyleString = Object.entries(divStyle)
            .map(([key, value]) => `${key}: ${value};`)
            .join(' ');
        // console.log('divStyleString', divStyleString)
        const p = p_2 ? 'p-2 p-2-Tbody' : '';
        const truncate = `overflow-ellipsis overflow-hidden`;
        const { dataIndex, renderer } = column;
        const controlId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`;
        const cellTd = document.getElementById(`${controlId}__td`);
        if (cellTd) {
            cellTd.className = (0, tailwind_merge_1.twMerge)(cellTd.className, tdClass, p);
            cellTd.style.cssText = tdStyleString;
            cellTd.title = tdTooltip;
            cellTd.onmousemove = () => {
                // console.log('onmousemove', e)
                if (applyOnMouseMoveScript) {
                    applyOnMouseMoveScript();
                }
            };
        }
        const cellDiv = document.getElementById(`${controlId}__div`);
        if (cellDiv) {
            // console.log('cellDiv', cellId, truncate, divClass, cellDiv.className)
            cellDiv.className = (0, tailwind_merge_1.twMerge)(truncate, divClass, cellDiv.className);
            cellDiv.style.cssText = `${divStyleString}`;
            cellDiv.title = divTooltip;
            const animationDelay = tableConfig.animationDelay || 0;
            cellDiv.innerHTML = rendered;
            cellDiv.setAttribute('data-component-case', componentCase);
            if (animationDelay) {
                cellDiv.classList.add('fade-in');
            }
            else {
                cellDiv.classList.add('visible');
            }
            setTimeout(() => {
                // if (column.dataIndex === 'user_id')
                // console.log('applyPostRenderScript', column.renderer, applyPostRenderScript)
                applyPostRenderScript();
                applyOnChangeScript();
                // applyOnMouseMoveScript()
                if (animationDelay)
                    cellDiv.classList.add('visible');
            }, Math.random() * (tableConfig.animationDelay || 0));
        }
    });
};
exports.applyRenderedTRow = applyRenderedTRow;
//# sourceMappingURL=EditableTable3ApplyRenderedTRow.js.map