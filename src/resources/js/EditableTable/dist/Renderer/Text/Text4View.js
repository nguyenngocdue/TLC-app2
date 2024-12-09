"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Text4View = void 0;
const Renderer4View_1 = require("../Renderer4View");
class Text4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `whitespace-nowrap`;
        this.divClass = `w-40 truncate`;
    }
    control() {
        const rendered = this.cellValue;
        return rendered;
    }
}
exports.Text4View = Text4View;
//# sourceMappingURL=Text4View.js.map