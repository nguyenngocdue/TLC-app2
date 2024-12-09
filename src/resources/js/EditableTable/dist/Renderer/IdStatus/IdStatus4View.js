"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.IdStatus4View = void 0;
const Renderer4View_1 = require("../Renderer4View");
const Shared_1 = require("./Shared");
class IdStatus4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `whitespace-nowrap`;
    }
    control() {
        const column = this.column;
        const allowOpen = false;
        return (0, Shared_1.renderColumn4)(column, this.cellValue, allowOpen);
    }
}
exports.IdStatus4View = IdStatus4View;
//# sourceMappingURL=IdStatus4View.js.map