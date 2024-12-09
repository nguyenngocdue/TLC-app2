"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.PickerDateTime4 = void 0;
const PickerDateTime4Edit_1 = require("./PickerDateTime4Edit");
const PickerDateTime4View_1 = require("./PickerDateTime4View");
class PickerDateTime4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new PickerDateTime4Edit_1.PickerDateTime4Edit(this.params).render();
            default:
                return new PickerDateTime4View_1.PickerDateTime4View(this.params).render();
        }
    }
}
exports.PickerDateTime4 = PickerDateTime4;
//# sourceMappingURL=PickerDateTime4.js.map