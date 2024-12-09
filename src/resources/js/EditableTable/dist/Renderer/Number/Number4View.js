"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Number4View = void 0;
const Renderer4View_1 = require("../Renderer4View");
class Number4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-right`;
    }
    control() {
        const { cellValue } = this;
        const column = this.column;
        const { decimalPlaces } = column.rendererAttrs || {};
        const value = cellValue ? (cellValue * 1).toFixed(decimalPlaces) : '';
        return value;
    }
}
exports.Number4View = Number4View;
//# sourceMappingURL=Number4View.js.map