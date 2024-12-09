"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.renderColumn4 = void 0;
const Functions_1 = require("../../Functions");
const renderColumn4 = (column, cellValue, allowOpen) => {
    // const column = column as TableColumnColumn
    const rendererAttrs = column.rendererAttrs || {};
    const { statusColumn = 'status', nameColumn = 'name' } = rendererAttrs;
    const merged = (0, Functions_1.getForeignObjects)(cellValue);
    // console.log(merged)
    const renderStatus = (foreignObject) => {
        const idKey = foreignObject['id'];
        const idStr = Functions_1.Str.makeId(idKey);
        const tooltip = foreignObject[nameColumn];
        const statusKey = foreignObject[statusColumn];
        const statuses = (0, Functions_1.getDataSourceFromK)('statuses', 'name');
        // console.log('renderStatus', statuses, statusKey)
        const status = statuses[statusKey];
        const { text_color, bg_color } = status;
        const classList = `bg-${text_color} text-${bg_color} hover:bg-${bg_color} hover:text-${text_color} rounded whitespace-nowrap font-semibold text-xs-vw text-xs mx-0.5 px-2 py-1 leading-7 `;
        return `<span 
            class="${classList}"
            title="${tooltip}"
            >${idStr}</span>`;
    };
    let arrayOfRendered = [];
    arrayOfRendered = merged.map((foreignObject) => {
        if (!foreignObject)
            return '';
        const statusKey = foreignObject[statusColumn];
        if (!statusKey)
            return '';
        const rendered = renderStatus(foreignObject);
        if (!allowOpen)
            return `${rendered}`;
        const href = foreignObject.href || 'define-href-in-the-cell';
        return `<a href="${href}" target="_blank">${rendered}</a>`;
    });
    const rendered = arrayOfRendered.join(` `);
    return rendered;
};
exports.renderColumn4 = renderColumn4;
//# sourceMappingURL=Shared.js.map