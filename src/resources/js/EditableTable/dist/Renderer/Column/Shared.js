"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.renderColumn4 = void 0;
const Functions_1 = require("../../Functions");
const renderColumn4 = (column, cellValue, allowOpen) => {
    // const column = column as TableColumnColumn
    const rendererAttrs = column.rendererAttrs || {};
    const { columnToLoad = 'name' } = rendererAttrs;
    const merged = (0, Functions_1.getForeignObjects)(cellValue);
    // console.log(merged)
    let arrayOfRendered = [];
    if (columnToLoad) {
        arrayOfRendered = merged.map((foreignObject) => {
            if (foreignObject && foreignObject[columnToLoad]) {
                const s = foreignObject[columnToLoad];
                if (allowOpen) {
                    const href = foreignObject.href || 'define-href-in-the-cell';
                    return `<a href="${href}" target="_blank">
                    <button class="text-xs text-xs-vw font-semibold rounded px-2 py-1 bg-blue-500 hover:bg-blue-700 text-white">${s}</button>
                </a>`;
                }
                else
                    return `${s}`;
            }
            return '';
        });
    }
    const rendered = arrayOfRendered.join(`${allowOpen ? ' ' : ', '}`);
    return rendered;
};
exports.renderColumn4 = renderColumn4;
//# sourceMappingURL=Shared.js.map