"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.HyperLink4View = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const Renderer4View_1 = require("../Renderer4View");
class HyperLink4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
        this.divStyle = { width: '60px' };
    }
    control() {
        var _a;
        const { cellValue, tableConfig } = this;
        const column = this.column;
        const rendererAttrs = column.rendererAttrs || {};
        const { target = '_blank' } = rendererAttrs;
        const classList = (0, tailwind_merge_1.twMerge)((_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.button, `text-xs text-xs-vw font-semibold text-white bg-blue-500 hover:bg-blue-700 py-1 px-2 rounded`);
        const value = cellValue
            ? `<a href="${cellValue}" target="${target}" ><button class="${classList}">Open</button></a>`
            : ``;
        return value;
    }
}
exports.HyperLink4View = HyperLink4View;
//# sourceMappingURL=HyperLink4View.js.map