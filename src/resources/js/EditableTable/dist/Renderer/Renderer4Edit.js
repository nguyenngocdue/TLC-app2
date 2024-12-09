"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Renderer4Edit = void 0;
const Renderer4View_1 = require("./Renderer4View");
class Renderer4Edit extends Renderer4View_1.Renderer4View {
    setValueToTableData(value) {
        const { tableName, rowIndex, dataIndex } = this;
        const control = document.getElementById(this.controlId);
        const cellValue = value !== undefined ? value : control.value;
        const before = tableData[tableName].data[rowIndex][dataIndex];
        tableData[tableName].data[rowIndex][dataIndex] = cellValue;
        console.log('setValueToTableData', tableName, rowIndex, dataIndex, before, '==>', cellValue);
    }
    render() {
        return {
            rendered: this.control(),
            tdClass: this.tdClass,
            divClass: this.divClass,
            applyPostRenderScript: this.applyPostRenderScript.bind(this),
            applyOnMouseMoveScript: this.applyOnMouseMoveScript.bind(this),
            applyOnChangeScript: this.applyOnChangeScript.bind(this),
        };
    }
}
exports.Renderer4Edit = Renderer4Edit;
//# sourceMappingURL=Renderer4Edit.js.map