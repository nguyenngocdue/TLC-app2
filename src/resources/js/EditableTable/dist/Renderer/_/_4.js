"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Text4 = void 0;
const _4Edit_1 = require("./_4Edit");
const _4View_1 = require("./_4View");
class Text4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new _4Edit_1.Text4Edit(this.params).render();
            default:
                return new _4View_1.Text4View(this.params).render();
        }
    }
}
exports.Text4 = Text4;
//# sourceMappingURL=_4.js.map