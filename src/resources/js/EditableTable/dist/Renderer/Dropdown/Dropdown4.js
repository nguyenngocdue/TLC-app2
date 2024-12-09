"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Dropdown4 = void 0;
const Dropdown4Edit_1 = require("./Dropdown4Edit");
const Dropdown4View_1 = require("./Dropdown4View");
class Dropdown4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Dropdown4Edit_1.Dropdown4Edit(this.params).render();
            default:
                return new Dropdown4View_1.Dropdown4View(this.params).render();
        }
    }
}
exports.Dropdown4 = Dropdown4;
//# sourceMappingURL=Dropdown4.js.map