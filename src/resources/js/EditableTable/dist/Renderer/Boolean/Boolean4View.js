"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Boolean4View = void 0;
const Renderer4View_1 = require("../Renderer4View");
class Boolean4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
    }
    control() {
        const { cellValue } = this;
        const value = cellValue ? `<i class="fas fa-circle-check text-green-500 text-lg"></i>` : ``;
        return value;
    }
}
exports.Boolean4View = Boolean4View;
//# sourceMappingURL=Boolean4View.js.map