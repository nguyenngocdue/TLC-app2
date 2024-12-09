"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Status4View = void 0;
const CacheKByKey_1 = require("../../Functions/CacheKByKey");
const Renderer4View_1 = require("../Renderer4View");
class Status4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = 'text-center';
    }
    control() {
        const { cellValue } = this;
        const value = cellValue;
        const statuses = (0, CacheKByKey_1.getDataSourceFromK)('statuses', 'name');
        const status = statuses[value] || {};
        // console.log('Status4View.render', status)
        const { text_color = 'gray', bg_color = 'pink' } = status;
        const classList = `bg-${text_color} text-${bg_color} hover:bg-${bg_color} hover:text-${text_color} rounded whitespace-nowrap font-semibold text-xs-vw text-xs mx-0.5 px-2 py-1 leading-7 `;
        const rendered = `<span class="${classList}" title="${value}">${status.title}</span>`;
        return rendered;
    }
}
exports.Status4View = Status4View;
//# sourceMappingURL=Status4View.js.map