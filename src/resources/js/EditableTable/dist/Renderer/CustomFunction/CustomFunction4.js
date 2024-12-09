"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.CustomFunction4 = void 0;
const CustomFunction4Edit_1 = require("./CustomFunction4Edit");
const CustomFunction4View_1 = require("./CustomFunction4View");
class CustomFunction4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new CustomFunction4Edit_1.CustomFunction4Edit(this.params).render();
            default:
                return new CustomFunction4View_1.CustomFunction4View(this.params).render();
        }
    }
}
exports.CustomFunction4 = CustomFunction4;
//# sourceMappingURL=CustomFunction4.js.map