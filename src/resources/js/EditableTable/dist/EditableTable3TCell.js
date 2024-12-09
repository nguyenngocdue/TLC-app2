"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeTCell = void 0;
const ValueObject4_1 = require("./Renderer/ValueObject/ValueObject4");
const Functions_1 = require("./Functions");
const Text4_1 = require("./Renderer/Text/Text4");
const Dropdown4_1 = require("./Renderer/Dropdown/Dropdown4");
const Toggle4_1 = require("./Renderer/Toggle/Toggle4");
const Number4_1 = require("./Renderer/Number/Number4");
const Checkbox4_1 = require("./Renderer/Checkbox/Checkbox4");
const PickerDateTime4_1 = require("./Renderer/PickerDateTime/PickerDateTime4");
const IdLink_1 = require("./Renderer/IdAction/IdLink");
const LineNo_1 = require("./Renderer/IdAction/LineNo");
const ActionPrint_1 = require("./Renderer/IdAction/ActionPrint");
const ActionColumn_1 = require("./Renderer/IdAction/ActionColumn");
// import { ActionCheckbox } from './Renderer/IdAction/ActionCheckbox'
const HyperLink4View_1 = require("./Renderer/HyperLink/HyperLink4View");
const Column4View_1 = require("./Renderer/Column/Column4View");
const ColumnLink4View_1 = require("./Renderer/Column/ColumnLink4View");
const IdStatus4View_1 = require("./Renderer/IdStatus/IdStatus4View");
const IdStatusLink4View_1 = require("./Renderer/IdStatus/IdStatusLink4View");
const Status4View_1 = require("./Renderer/Status/Status4View");
const AggCount4View_1 = require("./Renderer/Aggregations/AggCount4View");
const Thumbnail4View_1 = require("./Renderer/Thumbnail/Thumbnail4View");
const AvatarUser4View_1 = require("./Renderer/AvatarUser/AvatarUser4View");
const Attachment4_1 = require("./Renderer/Attachment/Attachment4");
const CheckboxForLine_1 = require("./Renderer/IdAction/CheckboxForLine");
const makeTCell = (params, dataLine, column, rowIndex) => {
    let cellValue = dataLine[column.dataIndex];
    let rendered = '';
    let tdClass = '';
    let tdStyle = {};
    let tdTooltip = '';
    let divClass = '';
    let divStyle = {};
    let divTooltip = '';
    let p_2 = true;
    let result = { rendered: `` };
    let componentCase = 'not-yet-defined';
    let applyPostRenderScript = () => { };
    let applyOnMouseMoveScript = () => { };
    let applyOnChangeScript = () => { };
    // console.log(column.dataIndex, column)
    const { tableName } = params;
    const { dataIndex, renderer } = column;
    // const controlName = `${tableName}[l${rowIndex}][${dataIndex}]`
    // const controlName = `${tableName}[${dataIndex}][${rowIndex}]`
    const controlId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`;
    const rendererParams = {
        // controlName,
        controlId,
        cellValue,
        params,
        dataLine,
        column,
        rowIndex,
    };
    switch (true) {
        case renderer == 'doc-id':
            rendered = `FAKE-DOC-ID-${cellValue}`;
            tdClass = 'whitespace-nowrap';
            break;
        // case renderer == 'custom_function':
        //     rendererParams.customRenderFn = () => {
        //         return {
        //             rendered: `Hello ${cellValue}`,
        //             tdClass: '',
        //             divClass: '',
        //             tdStyle: {},
        //             divStyle: {},
        //             applyPostRenderScript: () => {},
        //             applyOnMouseMoveScript: () => {},
        //             applyOnChangeScript: () => {},
        //         }
        //     }
        //     result = new CustomFunction4(rendererParams).render()
        //     break
        case renderer == 'no.':
            result = new LineNo_1.LineNo(rendererParams).render();
            break;
        case renderer == 'id_link':
        case renderer == 'id': //Obsolete
            result = new IdLink_1.IdLink(rendererParams).render();
            break;
        case renderer == 'qr-code':
        case renderer == 'action_print.':
            result = new ActionPrint_1.ActionPrint(rendererParams).render();
            break;
        case renderer == 'action_column': //Obsolete
            result = new ActionColumn_1.ActionColumn(rendererParams).render();
            break;
        // case renderer == 'action_checkbox.':
        // case renderer == 'checkbox_column': //Obsolete
        // result = new ActionCheckbox(rendererParams).render()
        // break
        case renderer == 'hyper-link':
            result = new HyperLink4View_1.HyperLink4View(rendererParams).render();
            break;
        case renderer == 'column':
            result = new Column4View_1.Column4View(rendererParams).render();
            break;
        case renderer == 'parent_link':
        case renderer == 'column_link':
            result = new ColumnLink4View_1.ColumnLink4View(rendererParams).render();
            break;
        case renderer == 'id_status':
            result = new IdStatus4View_1.IdStatus4View(rendererParams).render();
            break;
        case renderer == 'id_status_link':
            result = new IdStatusLink4View_1.IdStatusLink4View(rendererParams).render();
            break;
        case renderer == 'status':
            result = new Status4View_1.Status4View(rendererParams).render();
            break;
        case renderer == 'thumbnail': // this line will be removed as overlap with attachment
        case renderer == 'thumbnails': // this line will be removed for new flexible MODE
            result = new Thumbnail4View_1.Thumbnail4View(rendererParams).render();
            break;
        case renderer == 'attachment':
            result = new Attachment4_1.Attachment4(rendererParams).render();
            break;
        case renderer == 'avatar_user':
            result = new AvatarUser4View_1.AvatarUser4View(rendererParams).render();
            break;
        case renderer == 'agg_count':
            result = new AggCount4View_1.AggCount4View(rendererParams).render();
            break;
        //============From here there is CONTROL renderer================
        case renderer == 'text':
        case renderer == 'text4': // this line will be removed for new flexible MODE
            result = new Text4_1.Text4(rendererParams).render();
            break;
        case renderer == 'number':
        case renderer == 'number4': // this line will be removed for new flexible MODE
            result = new Number4_1.Number4(rendererParams).render();
            break;
        case renderer == 'dropdown':
        case renderer == 'dropdown4': // this line will be removed for new flexible MODE
            result = new Dropdown4_1.Dropdown4(rendererParams).render();
            break;
        case renderer == 'toggle':
        case renderer == 'toggle4': // this line will be removed for new flexible MODE
            result = new Toggle4_1.Toggle4(rendererParams).render();
            break;
        case renderer == 'checkbox':
        case renderer == 'checkbox4': // this line will be removed for new flexible MODE
            result = new Checkbox4_1.Checkbox4(rendererParams).render();
            break;
        case renderer == 'checkbox_for_line':
        case renderer == 'checkbox_column': //Obsolete
            result = new CheckboxForLine_1.CheckboxForLine(rendererParams).render();
            break;
        case renderer == 'picker_datetime':
        case renderer == 'date-time':
            result = new PickerDateTime4_1.PickerDateTime4(rendererParams).render();
            break;
        case renderer != undefined:
            rendered = `Unknown renderer: ${renderer}`;
            tdClass = 'bg-red-200';
            componentCase = 'column.renderer.undefined';
            break;
        //============From here there is no renderer================
        case (0, Functions_1.smartTypeOf)(cellValue) == 'string':
            rendered = cellValue;
            componentCase = 'smartTypeOf(cellValue).string';
            break;
        case (0, Functions_1.smartTypeOf)(cellValue) == 'number':
            rendered = cellValue;
            componentCase = 'smartTypeOf(cellValue).number';
            break;
        case (0, Functions_1.smartTypeOf)(cellValue) == 'boolean':
            rendered = cellValue;
            componentCase = 'smartTypeOf(cellValue).boolean';
            break;
        case (0, Functions_1.smartTypeOf)(cellValue) == 'object':
            result = new ValueObject4_1.ValueObject4(rendererParams).render();
            rendered = result.rendered;
            tdClass = result.tdClass;
            divClass = result.divClass;
            tdStyle = result.tdStyle || {};
            divStyle = result.divStyle || {};
            tdTooltip = result.tdTooltip || ``;
            divTooltip = result.divTooltip || ``;
            p_2 = false;
            componentCase = 'smartTypeOf(cellValue).object';
            break;
        case (0, Functions_1.smartTypeOf)(cellValue) == 'array':
            const array = cellValue;
            const values = array.map((item) => {
                const rendererParams1 = {
                    controlId,
                    cellValue: item,
                    params,
                    dataLine,
                    column,
                    rowIndex,
                };
                return new ValueObject4_1.ValueObject4(rendererParams1).render();
            });
            // console.log('values', values)
            // rendered = 'aaaaa'
            const { arraySeparator = ' ' } = column;
            rendered = values.map((v) => v.rendered).join(arraySeparator);
            tdClass = values[0].tdClass;
            p_2 = false;
            componentCase = 'smartTypeOf(cellValue).array';
            break;
        //============From here there is render base on cellValue================
        case cellValue === null:
            rendered = '';
            componentCase = 'cellValue.null';
            break;
        case cellValue === undefined:
            rendered = '';
            componentCase = 'cellValue.undefined';
            break;
        default:
            rendered = `Unknown how to render this item: ${cellValue}`;
            componentCase = 'default.unknown';
            break;
    }
    if (renderer) {
        componentCase = `column.renderer.${renderer}`;
        rendered = result.rendered || rendered;
        tdClass = result.tdClass || tdClass;
        tdStyle = result.tdStyle || tdStyle;
        tdTooltip = result.tdTooltip || tdTooltip;
        divClass = result.divClass || divClass;
        divStyle = result.divStyle || divStyle;
        divTooltip = result.divTooltip || divTooltip;
        applyPostRenderScript = result.applyPostRenderScript || (() => { });
        applyOnMouseMoveScript = result.applyOnMouseMoveScript || (() => { });
        applyOnChangeScript = result.applyOnChangeScript || (() => { });
        if (column.mode == 'edit')
            p_2 = false;
    }
    // console.log(rendered)
    if (!divStyle['width'])
        divStyle['width'] = `${column.width || 100}px`;
    return {
        rendered,
        tdClass,
        tdStyle,
        tdTooltip,
        divClass,
        divStyle,
        divTooltip,
        p_2,
        componentCase,
        applyPostRenderScript,
        applyOnMouseMoveScript,
        applyOnChangeScript,
    };
};
exports.makeTCell = makeTCell;
//# sourceMappingURL=EditableTable3TCell.js.map