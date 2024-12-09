"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Toggle4Edit = void 0;
const Renderer4Edit_1 = require("../Renderer4Edit");
class Toggle4Edit extends Renderer4Edit_1.Renderer4Edit {
    applyOnChangeScript() {
        const input = document.getElementById(this.controlId);
        input &&
            input.addEventListener('change', () => this.setValueToTableData(input.checked));
    }
    control() {
        var _a;
        const { controlId, tableConfig, cellValue } = this;
        const classList = (_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.toggle;
        const checked = !!cellValue ? 'checked' : '';
        return `<div class="flex justify-center">
            <label for="${controlId}" class="inline-flex relative items-center cursor-pointer">
                <input type="checkbox" id="${controlId}" class="sr-only peer" ${checked} value="true">
                <div class="${classList}"></div>
            </label>
        </div>`;
    }
}
exports.Toggle4Edit = Toggle4Edit;
//# sourceMappingURL=Toggle4Edit.js.map