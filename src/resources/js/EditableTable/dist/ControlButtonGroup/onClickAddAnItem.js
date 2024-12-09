"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.onClickAddAnItem = void 0;
const moment_1 = __importDefault(require("moment"));
const EditableTable3ApplyRenderedTRow_1 = require("../EditableTable3ApplyRenderedTRow");
const EditableTable3ParamType_1 = require("../Type/EditableTable3ParamType");
const updateVirtualTableVisibleRows_1 = require("../VirtualScrolling/updateVirtualTableVisibleRows");
const TableManipulations_1 = require("../Functions/TableManipulations");
const defaultValueForPk = (column) => {
    var _a;
    const pickerType = ((_a = column.rendererAttrs) === null || _a === void 0 ? void 0 : _a.pickerType) || 'datetime';
    // console.log('pickerType', pickerType)
    switch (pickerType) {
        case 'time':
            // return moment().format('HH:mm:ss')
            return '01:00:00';
        case 'datetime':
            return moment_1.default.utc().format('YYYY-MM-DD HH:mm:ss');
        case 'date':
        case 'month':
        case 'year':
        default:
            return (0, moment_1.default)().format('YYYY-MM-DD');
    }
};
const onClickAddAnItem = (params) => {
    const { tableName } = params;
    const dataSource = tableData[tableName];
    const columns = tableColumns[tableName];
    const newIndex = dataSource.data.length;
    const newItem = {};
    columns.forEach((column) => {
        if (column.renderer == 'no.')
            return;
        let value;
        switch (true) {
            case column.renderer == 'picker_datetime':
                const tmp1 = defaultValueForPk(column);
                // console.log('tmp1', tmp1)
                value = tmp1;
                break;
            case column.renderer == 'number':
            case column.renderer == 'number4':
                value = 0;
                break;
            case column.renderer == 'toggle':
            case column.renderer == 'toggle4':
            case column.renderer == 'checkbox':
            case column.renderer == 'checkbox4':
                value = false;
                break;
            //==================
            case column.dataIndex == 'order_no':
                value = (newIndex + 1);
                break;
            case column.dataIndex == 'name':
                value = `Line ${newIndex + 1}`;
                break;
            default:
                value = null;
                break;
        }
        newItem[column.dataIndex] = value;
        // console.log('adding column', column)
    });
    // console.log('newItem', newItem)
    dataSource.data.push(Object.assign(Object.assign({}, newItem), { NEW_INSERTED_LINE: true }));
    const index = dataSource.data.length - 1;
    const emptyRow = (0, updateVirtualTableVisibleRows_1.renderOneEmptyRow)(params, index, EditableTable3ParamType_1.Caller.ON_CLICK_ADD_AN_ITEM);
    if (!emptyRow)
        return;
    (0, TableManipulations_1.addTrBeforeBtmSpacer)(tableName, emptyRow);
    (0, EditableTable3ApplyRenderedTRow_1.applyRenderedTRow)(params, newItem, index);
    (0, TableManipulations_1.scrollToBottom)(tableName);
    (0, TableManipulations_1.addClassToTr)(tableName, index, 'bg-green-100');
};
exports.onClickAddAnItem = onClickAddAnItem;
//# sourceMappingURL=onClickAddAnItem.js.map