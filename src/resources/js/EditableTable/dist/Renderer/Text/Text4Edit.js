"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Text4Edit = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const Renderer4Edit_1 = require("../Renderer4Edit");
// declare let tableData: { [tableName: string]: LengthAware }
class Text4Edit extends Renderer4Edit_1.Renderer4Edit {
    applyOnChangeScript() {
        const control = document.getElementById(this.controlId);
        control && control.addEventListener('change', () => this.setValueToTableData());
    }
    control() {
        var _a;
        const { tableConfig, column } = this;
        const classList = (0, tailwind_merge_1.twMerge)(`${(_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.text} ${column.classList}`);
        const cellValue = this.cellValue;
        const { controlId } = this;
        const html = `<input 
            component="text4edit" 
            id="${controlId}" 
            type="text" 
            class="${classList}" 
            value="${cellValue}"
        />`;
        return html;
    }
}
exports.Text4Edit = Text4Edit;
//# sourceMappingURL=Text4Edit.js.map