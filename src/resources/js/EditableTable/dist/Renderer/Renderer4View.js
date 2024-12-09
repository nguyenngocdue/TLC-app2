"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Renderer4View = void 0;
class Renderer4View {
    constructor(params) {
        this.params = params;
        this.tableDebug = false;
        this.tdStyle = {};
        this.tdTooltip = '';
        this.divStyle = {};
        this.divTooltip = '';
        this.cellValue = this.params.cellValue;
        this.column = this.params.column;
        this.tableName = this.params.params.tableName;
        this.dataIndex = this.params.column.dataIndex;
        this.rowIndex = this.params.rowIndex;
        this.dataLine = this.params.dataLine;
        this.controlId = this.params.controlId;
        this.tableConfig = this.params.params.tableConfig;
        this.tableParams = this.params.params;
        this.customRenderFn = this.params.customRenderFn;
        this.tdClass = '';
        this.divClass = '';
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
    applyPostRenderScript() { }
    applyOnMouseMoveScript() { }
    applyOnChangeScript() { }
    getTableRendererParams() {
        const result = {
            controlId: this.controlId,
            cellValue: this.cellValue,
            params: this.tableParams,
            dataLine: this.dataLine,
            column: this.column,
            rowIndex: this.rowIndex,
        };
        return result;
    }
}
exports.Renderer4View = Renderer4View;
//# sourceMappingURL=Renderer4View.js.map