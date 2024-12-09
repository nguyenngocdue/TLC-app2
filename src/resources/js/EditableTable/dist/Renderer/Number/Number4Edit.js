"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Number4Edit = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const Renderer4Edit_1 = require("../Renderer4Edit");
class Number4Edit extends Renderer4Edit_1.Renderer4Edit {
    applyOnChangeScript() {
        const control = document.getElementById(this.controlId);
        control && control.addEventListener('change', () => this.setValueToTableData());
    }
    control() {
        var _a;
        const { cellValue, controlId, tableConfig } = this;
        const column = this.column;
        const { decimalPlaces = 0 } = column.rendererAttrs || {};
        const classList = (0, tailwind_merge_1.twMerge)(`text-right`, (_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.text);
        const value = cellValue ? (cellValue * 1).toFixed(decimalPlaces) : '';
        const step = Math.pow(10, -decimalPlaces);
        // console.log('step', step, decimalPlaces)
        const html = `<input 
            component="text4edit" 
            id="${controlId}"
            class="${classList}" 
            value="${value}" 
            type="number" 
            step="${step}" 
            />`;
        return html;
    }
}
exports.Number4Edit = Number4Edit;
//# sourceMappingURL=Number4Edit.js.map