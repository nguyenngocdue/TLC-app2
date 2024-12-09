"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Toggle4 = void 0;
const Toggle4Edit_1 = require("./Toggle4Edit");
const Boolean4View_1 = require("../Boolean/Boolean4View");
class Toggle4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Toggle4Edit_1.Toggle4Edit(this.params).render();
            default:
                return new Boolean4View_1.Boolean4View(this.params).render();
        }
    }
}
exports.Toggle4 = Toggle4;
//# sourceMappingURL=Toggle4.js.map