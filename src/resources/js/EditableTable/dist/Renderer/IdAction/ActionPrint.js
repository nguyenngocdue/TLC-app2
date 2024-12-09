"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ActionPrint = void 0;
const Renderer4View_1 = require("../Renderer4View");
class ActionPrint extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
        this.divClass = `mx-auto`;
        this.divStyle = { width: '30px' };
    }
    control() {
        var _a;
        const column = this.column;
        const entityName = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.entityName) || '/dashboard/unknown-entity';
        const id = `<i class="fa-duotone fa-print"></i>`;
        const href = `${entityName}/${this.cellValue}`;
        return `<a class="text-blue-600 p-2 rounded" href="${href}">${id}</a>`;
    }
}
exports.ActionPrint = ActionPrint;
//# sourceMappingURL=ActionPrint.js.map