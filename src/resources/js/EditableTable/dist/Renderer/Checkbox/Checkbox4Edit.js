"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Checkbox4Edit = void 0;
const Renderer4Edit_1 = require("../Renderer4Edit");
class Checkbox4Edit extends Renderer4Edit_1.Renderer4Edit {
    constructor() {
        super(...arguments);
        this.tdClass = 'text-center';
    }
    applyOnChangeScript() {
        const input = document.getElementById(this.controlId);
        if (!input)
            return;
        input.addEventListener('change', () => {
            this.setValueToTableData(input.checked);
            if (this.column.dataIndex == '_checkbox_for_line_') {
                console.log('checkbox for line');
                //get all value of checkbox for line, if any is true, show the master button group
                const dataSource = tableData[this.tableName];
                const isAnyChecked = dataSource.data.some((data) => data._checkbox_for_line_);
                const masterButtonGroup = document.getElementById(`${this.tableName}__master_button_group`);
                if (masterButtonGroup) {
                    masterButtonGroup.classList.toggle('hidden', !isAnyChecked);
                }
            }
        });
    }
    control() {
        var _a;
        const { controlId, tableConfig, cellValue } = this;
        const classList = (_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.toggle_checkbox;
        // const column = this.column as TableColumnCheckbox
        // console.log('column', column)
        const checked = !!cellValue ? 'checked' : '';
        return `<input id="${controlId}" type="checkbox" class="${classList}" ${checked} value="true"/>`;
    }
}
exports.Checkbox4Edit = Checkbox4Edit;
//# sourceMappingURL=Checkbox4Edit.js.map