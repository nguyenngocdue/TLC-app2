"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.PickerDateTime4View = void 0;
const moment_1 = __importDefault(require("moment"));
const Renderer4View_1 = require("../Renderer4View");
const Shared_1 = require("./Shared");
class PickerDateTime4View extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
    }
    control() {
        var _a;
        const value = this.cellValue;
        const column = this.column;
        const pickerType = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.pickerType) || 'datetime';
        let rendered = '';
        if (this.cellValue) {
            switch (pickerType) {
                case 'datetime':
                    rendered = (0, moment_1.default)(value).format('DD/MM/YYYY HH:mm');
                    break;
                case 'date':
                    rendered = (0, moment_1.default)(value).format('DD/MM/YYYY');
                    break;
                case 'time':
                    rendered = (0, moment_1.default)(value).format('HH:mm');
                    break;
                case 'month':
                    rendered = (0, moment_1.default)(value).format('MM/YYYY');
                    break;
                case 'year':
                    rendered = (0, moment_1.default)(value).format('YYYY');
                    break;
                default:
                    rendered = 'unknown how to render pickerType: ' + pickerType;
                    break;
            }
            this.divStyle['width'] = (0, Shared_1.defaultWidth)(pickerType) + 'px';
        }
        return rendered;
    }
}
exports.PickerDateTime4View = PickerDateTime4View;
//# sourceMappingURL=PickerDateTime4View.js.map