"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Dropdown4Edit = void 0;
const Functions_1 = require("../../Functions");
const Renderer4Edit_1 = require("../Renderer4Edit");
class Dropdown4Edit extends Renderer4Edit_1.Renderer4Edit {
    constructor() {
        super(...arguments);
        this.tableDebug = false;
        this.getOptionsExpensive = (column) => {
            const { rendererAttrs = {} } = column;
            const cbbDataSource = (0, Functions_1.getDataSource)(column);
            const { valueField = 'id', labelField = 'name',
            // descriptionField = 'description',
            // tooltipField = '',
             } = rendererAttrs;
            const options = Object.keys(cbbDataSource).map((key) => {
                const item = cbbDataSource[key];
                // const tooltip = item[tooltipField] || Str.makeId(item[valueField])
                const option = {
                    id: item[valueField],
                    text: item[labelField],
                };
                return option;
                // return `<option value="${item[valueField]}" title="${tooltip}">
                //     ${item[labelField]}
                // </option>`
            });
            return options;
        };
        this.getOptionsCheap = (column) => {
            const cellValue = this.cellValue;
            const { rendererAttrs = {} } = column;
            const cbbDataSource = (0, Functions_1.getDataSource)(column);
            const selectedItem = cbbDataSource[cellValue];
            const { valueField = 'id', labelField = 'name', 
            // descriptionField = 'description',
            tooltipField = valueField, } = rendererAttrs;
            if (!selectedItem)
                return ``;
            const item = cbbDataSource[selectedItem[valueField]];
            const tooltip = item[tooltipField] || Functions_1.Str.makeId(item[valueField]);
            // const fakeOption = `<option value="-1" title="Fake Option">-1</option>`
            const realOption = `<option value="${item[valueField]}" title="${tooltip}">
            ${item[labelField]}
        </option>`;
            return realOption;
        };
    }
    applyOnChangeScript() {
        $('#' + this.controlId).on('change', () => this.setValueToTableData());
    }
    applyOnMouseMoveScript() {
        // console.log('Dropdown4Edit.applyOnMouseMoveScript()')
        const dropdown = $('#' + this.controlId);
        if (!dropdown.data('select2')) {
            const column = this.column;
            const options = this.getOptionsExpensive(column);
            dropdown.select2({ data: options });
            this.applyOnChangeScript();
        }
        else {
            // console.log('Dropdown4Edit.applyOnMouseMoveScript() - select2 already initialized')
        }
    }
    control() {
        var _a;
        const classList = (_a = this.tableConfig.classList) === null || _a === void 0 ? void 0 : _a.dropdown_fake;
        // return this.cellValue as unknown as string
        const column = this.column;
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)
        // const options = this.getOptions(column)
        const optionsStr = this.getOptionsCheap(column);
        // <option class="text-gray-300" value="" disabled selected>Select an option</option>
        return `<select 
            id="${this.controlId}"             
            class="${classList} no-arrow"
            >
            ${optionsStr}
        </select>`;
    }
}
exports.Dropdown4Edit = Dropdown4Edit;
//# sourceMappingURL=Dropdown4Edit.js.map