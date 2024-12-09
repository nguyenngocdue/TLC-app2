"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Dropdown4View = void 0;
const CacheKByKey_1 = require("../../Functions/CacheKByKey");
const Renderer4View_1 = require("../Renderer4View");
class Dropdown4View extends Renderer4View_1.Renderer4View {
    control() {
        const cellValue = this.cellValue;
        const column = this.column;
        const { rendererAttrs = {} } = column;
        const { 
        // allowClear,
        // allowChooseWhenOneItem,
        // allowOpen,
        valueField = 'id', labelField = 'name', 
        // descriptionField = 'description',
        tooltipField = valueField,
        // filterColumns,
        // filterOperator,
        // filterValues,
        // dataSource,
        // dataSourceKey,
         } = rendererAttrs;
        const cbbDataSource = (0, CacheKByKey_1.getDataSource)(column);
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)
        const selectedItem = cbbDataSource[cellValue];
        // console.log(cellValue, selectedItem)
        let result = '';
        if (selectedItem) {
            const label = selectedItem[labelField];
            const tooltip = selectedItem[tooltipField] || '';
            result = `<div class="" title="${tooltip}">${label}</div>`;
        }
        else {
            result = (0, CacheKByKey_1.getNotFound)();
            this.tdClass = 'bg-gray-50';
        }
        return result;
    }
}
exports.Dropdown4View = Dropdown4View;
//# sourceMappingURL=Dropdown4View.js.map