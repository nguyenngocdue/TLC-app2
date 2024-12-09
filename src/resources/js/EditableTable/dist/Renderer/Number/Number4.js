"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Number4 = void 0;
const Number4Edit_1 = require("./Number4Edit");
const Number4View_1 = require("./Number4View");
class Number4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Number4Edit_1.Number4Edit(this.params).render();
            default:
                return new Number4View_1.Number4View(this.params).render();
        }
    }
}
exports.Number4 = Number4;
//# sourceMappingURL=Number4.js.map