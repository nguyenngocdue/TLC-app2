"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.LineNo = void 0;
const Renderer4View_1 = require("../Renderer4View");
class LineNo extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
    }
    control() {
        const rowIndex = this.rowIndex;
        const rendered = (rowIndex + 1).toString();
        this.divClass = this.tableConfig.orderable ? `drag-handle cursor-grab` : ``;
        return rendered;
    }
}
exports.LineNo = LineNo;
//# sourceMappingURL=LineNo.js.map