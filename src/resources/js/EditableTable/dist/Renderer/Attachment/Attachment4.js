"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Attachment4 = void 0;
const Thumbnail4View_1 = require("../Thumbnail/Thumbnail4View");
const Attachment4Edit_1 = require("./Attachment4Edit");
class Attachment4 {
    constructor(params) {
        this.params = params;
    }
    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Attachment4Edit_1.Attachment4Edit(this.params).render();
            default:
                return new Thumbnail4View_1.Thumbnail4View(this.params).render();
        }
    }
}
exports.Attachment4 = Attachment4;
//# sourceMappingURL=Attachment4.js.map