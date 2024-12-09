"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.IdLink = void 0;
const Functions_1 = require("../../Functions");
const Renderer4View_1 = require("../Renderer4View");
class IdLink extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
        this.divStyle = { width: '70px' };
    }
    control() {
        var _a;
        const column = this.column;
        // console.log(column.rendererAttrs)
        const entityName = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.entityName) || '/dashboard/unknown-entity';
        const rendered01 = this.cellValue;
        const rendered02 = this.cellValue;
        let id = 'cell-value-is-empty';
        switch (true) {
            case rendered01 !== undefined:
                id = Functions_1.Str.makeId(rendered01);
                break;
            case rendered02 !== undefined:
                id = rendered02;
                break;
        }
        const href = `${entityName}/${this.cellValue}/edit`;
        return `<a class="text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded" href="${href}">${id}</a>`;
    }
}
exports.IdLink = IdLink;
//# sourceMappingURL=IdLink.js.map