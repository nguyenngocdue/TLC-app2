"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.IdStatusLink4View = void 0;
const Renderer4View_1 = require("../Renderer4View");
const Shared_1 = require("./Shared");
class IdStatusLink4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `whitespace-nowrap`;
    }
    control() {
        const column = this.column;
        const allowOpen = true;
        return (0, Shared_1.renderColumn4)(column, this.cellValue, allowOpen);
    }
}
exports.IdStatusLink4View = IdStatusLink4View;
//# sourceMappingURL=IdStatusLink4View.js.map