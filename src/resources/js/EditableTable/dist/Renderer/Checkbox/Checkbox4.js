"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Checkbox4 = void 0;
const Boolean4View_1 = require("../Boolean/Boolean4View");
const Checkbox4Edit_1 = require("./Checkbox4Edit");
class Checkbox4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Checkbox4Edit_1.Checkbox4Edit(this.params).render();
            default:
                return new Boolean4View_1.Boolean4View(this.params).render();
        }
    }
}
exports.Checkbox4 = Checkbox4;
//# sourceMappingURL=Checkbox4.js.map