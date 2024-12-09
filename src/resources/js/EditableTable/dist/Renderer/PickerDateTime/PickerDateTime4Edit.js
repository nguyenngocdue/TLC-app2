"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.PickerDateTime4Edit = void 0;
const flatpickr_1 = __importDefault(require("flatpickr"));
const Renderer4Edit_1 = require("../Renderer4Edit");
const Shared_1 = require("./Shared");
const moment_1 = __importDefault(require("moment"));
class PickerDateTime4Edit extends Renderer4Edit_1.Renderer4Edit {
    constructor() {
        super(...arguments);
        this.tableDebug = false;
    }
    applyOnChangeScript() {
        var _a;
        const column = this.column;
        const pickerType = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.pickerType) || 'datetime';
        const element = document.getElementById(this.controlId);
        let date = '';
        if (element) {
            // console.log('PickerDateTime4Edit.applyOnChangeScript', element)
            element.addEventListener('change', () => {
                let value = element.value;
                const format = (0, Shared_1.getConfigFormat)(pickerType);
                switch (pickerType) {
                    case 'datetime':
                        value = (0, moment_1.default)(value).utc().format();
                        date = moment_1.default.utc(value).format(format);
                        break;
                    case 'date':
                    case 'month':
                    case 'year':
                        date = moment_1.default.utc(value).format(format);
                        break;
                    case 'time':
                        //Do nothing
                        date = value;
                        break;
                }
                // console.log(pickerType, value, date)
                this.setValueToTableData(date);
            });
        }
    }
    applyPostRenderScript() {
        var _a;
        const column = this.column;
        const pickerType = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.pickerType) || 'datetime';
        const element = document.getElementById(this.controlId);
        const value = this.cellValue;
        if (element) {
            const config = (0, Shared_1.getConfigJson)(pickerType);
            switch (pickerType) {
                case 'datetime':
                    config.defaultDate = moment_1.default.utc(value).local().toDate();
                    break;
                case 'time':
                    config.defaultDate = (0, moment_1.default)('1970-01-01T' + value, 'YYYY-MM-DDTHH:mm:ss').toDate();
                    break;
                default:
                    config.defaultDate = moment_1.default.utc(value).toDate();
            }
            (0, flatpickr_1.default)(element, config);
        }
        //remove the placeholder
        const placeholder = document.getElementById(this.controlId + '__placeholder');
        if (placeholder) {
            placeholder.remove();
        }
    }
    control() {
        var _a, _b, _c;
        const tableConfig = this.tableConfig;
        const column = this.column;
        const pickerType = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.pickerType) || 'datetime';
        return `<input 
            type="hidden" 
            id="${this.controlId}" 
            class="${(_b = tableConfig.classList) === null || _b === void 0 ? void 0 : _b.text}" 
            placeholder="${pickerType} input"
            value="${this.cellValue}"
        />
        <input id="${this.controlId}__placeholder" class="${(_c = tableConfig.classList) === null || _c === void 0 ? void 0 : _c.text}" />`;
    }
}
exports.PickerDateTime4Edit = PickerDateTime4Edit;
//# sourceMappingURL=PickerDateTime4Edit.js.map