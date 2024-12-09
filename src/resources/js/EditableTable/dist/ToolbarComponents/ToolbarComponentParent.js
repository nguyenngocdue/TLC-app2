"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ToolbarComponentParent = void 0;
class ToolbarComponentParent {
    constructor(params) {
        this.params = params;
        this.dataSource = tableData[params.tableName];
    }
    applyPostRenderScript() { }
    render() {
        return '';
    }
}
exports.ToolbarComponentParent = ToolbarComponentParent;
//# sourceMappingURL=ToolbarComponentParent.js.map