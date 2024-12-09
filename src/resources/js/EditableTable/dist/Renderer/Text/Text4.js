"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Text4 = void 0;
const Text4Edit_1 = require("./Text4Edit");
const Text4View_1 = require("./Text4View");
class Text4 {
    constructor(params) {
        this.params = params;
        // console.log('Text4', this.params)
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Text4Edit_1.Text4Edit(this.params).render();
            default:
                return new Text4View_1.Text4View(this.params).render();
        }
    }
}
exports.Text4 = Text4;
//# sourceMappingURL=Text4.js.map