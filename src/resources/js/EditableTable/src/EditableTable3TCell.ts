import { ValueObject4 } from './Renderer/ValueObject/ValueObject4'
import { smartTypeOf } from './Functions'
import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableValueObjectType, TableDataLine } from './Type/EditableTable3DataLineType'
import { TableRenderedValueObject, TableRendererParams } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'
import { Text4 } from './Renderer/Text/Text4'
import { Dropdown4 } from './Renderer/Dropdown/Dropdown4'
import { Toggle4 } from './Renderer/Toggle/Toggle4'
import { Number4 } from './Renderer/Number/Number4'
import { Checkbox4 } from './Renderer/Checkbox/Checkbox4'
import { PickerDateTime4 } from './Renderer/PickerDateTime/PickerDateTime4'
import { IdLink } from './Renderer/IdAction/IdLink'
import { LineNo } from './Renderer/IdAction/LineNo'
import { ActionPrint } from './Renderer/IdAction/ActionPrint'
import { ActionColumn } from './Renderer/IdAction/ActionColumn'
// import { ActionCheckbox } from './Renderer/IdAction/ActionCheckbox'
import { HyperLink4View } from './Renderer/HyperLink/HyperLink4View'
import { Column4View } from './Renderer/Column/Column4View'
import { ColumnLink4View } from './Renderer/Column/ColumnLink4View'
import { IdStatus4View } from './Renderer/IdStatus/IdStatus4View'
import { IdStatusLink4View } from './Renderer/IdStatus/IdStatusLink4View'
import { Status4View } from './Renderer/Status/Status4View'
import { AggCount4View } from './Renderer/Aggregations/AggCount4View'
import { Thumbnail4View } from './Renderer/Thumbnail/Thumbnail4View'
import { AvatarUser4View } from './Renderer/AvatarUser/AvatarUser4View'
import { Attachment4 } from './Renderer/Attachment/Attachment4'
import { CheckboxForLine } from './Renderer/IdAction/CheckboxForLine'

export const makeTCell = (
    params: TableParams,
    dataLine: TableDataLine,
    column: TableColumn,
    rowIndex: number,
) => {
    let cellValue = dataLine[column.dataIndex]
    let rendered: any = ''
    let tdClass: string | undefined = ''
    let tdStyle: { [key: string]: string | number } | undefined = {}
    let tdTooltip: string | undefined = ''

    let divClass: string | undefined = ''
    let divStyle: { [key: string]: string | number } | undefined = {}
    let divTooltip: string | undefined = ''

    let p_2 = true
    let result: TableRenderedValueObject = { rendered: `` }
    let componentCase = 'not-yet-defined'
    let applyPostRenderScript = () => {}
    let applyOnMouseMoveScript = () => {}
    let applyOnChangeScript = () => {}
    // console.log(column.dataIndex, column)

    const { tableName } = params
    const { dataIndex, renderer } = column
    // const controlName = `${tableName}[l${rowIndex}][${dataIndex}]`
    // const controlName = `${tableName}[${dataIndex}][${rowIndex}]`
    const controlId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`

    const rendererParams: TableRendererParams = {
        // controlName,
        controlId,
        cellValue,
        params,
        dataLine,
        column,
        rowIndex,
    }

    switch (true) {
        case renderer == 'doc-id':
            rendered = `FAKE-DOC-ID-${cellValue}`
            tdClass = 'whitespace-nowrap'
            break
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
            result = new LineNo(rendererParams).render()
            break
        case renderer == 'id_link':
        case renderer == 'id': //Obsolete
            result = new IdLink(rendererParams).render()
            break
        case renderer == 'qr-code':
        case renderer == 'action_print.':
            result = new ActionPrint(rendererParams).render()
            break
        case renderer == 'action_column': //Obsolete
            result = new ActionColumn(rendererParams).render()
            break
        // case renderer == 'action_checkbox.':
        // case renderer == 'checkbox_column': //Obsolete
        // result = new ActionCheckbox(rendererParams).render()
        // break
        case renderer == 'hyper-link':
            result = new HyperLink4View(rendererParams).render()
            break
        case renderer == 'column':
            result = new Column4View(rendererParams).render()
            break
        case renderer == 'parent_link':
        case renderer == 'column_link':
            result = new ColumnLink4View(rendererParams).render()
            break
        case renderer == 'id_status':
            result = new IdStatus4View(rendererParams).render()
            break
        case renderer == 'id_status_link':
            result = new IdStatusLink4View(rendererParams).render()
            break
        case renderer == 'status':
            result = new Status4View(rendererParams).render()
            break
        case renderer == 'thumbnail': // this line will be removed as overlap with attachment
        case renderer == 'thumbnails': // this line will be removed for new flexible MODE
            result = new Thumbnail4View(rendererParams).render()
            break
        case renderer == 'attachment':
            result = new Attachment4(rendererParams).render()
            break
        case renderer == 'avatar_user':
            result = new AvatarUser4View(rendererParams).render()
            break
        case renderer == 'agg_count':
            result = new AggCount4View(rendererParams).render()
            break

        //============From here there is CONTROL renderer================

        case renderer == 'text':
        case renderer == 'text4': // this line will be removed for new flexible MODE
            result = new Text4(rendererParams).render()
            break
        case renderer == 'number':
        case renderer == 'number4': // this line will be removed for new flexible MODE
            result = new Number4(rendererParams).render()
            break
        case renderer == 'dropdown':
        case renderer == 'dropdown4': // this line will be removed for new flexible MODE
            result = new Dropdown4(rendererParams).render()
            break
        case renderer == 'toggle':
        case renderer == 'toggle4': // this line will be removed for new flexible MODE
            result = new Toggle4(rendererParams).render()
            break
        case renderer == 'checkbox':
        case renderer == 'checkbox4': // this line will be removed for new flexible MODE
            result = new Checkbox4(rendererParams).render()
            break
        case renderer == 'checkbox_for_line':
        case renderer == 'checkbox_column': //Obsolete
            result = new CheckboxForLine(rendererParams).render()
            break
        case renderer == 'picker_datetime':
        case renderer == 'date-time':
            result = new PickerDateTime4(rendererParams).render()
            break
        case renderer != undefined:
            rendered = `Unknown renderer: ${renderer}`
            tdClass = 'bg-red-200'
            componentCase = 'column.renderer.undefined'
            break

        //============From here there is no renderer================
        case smartTypeOf(cellValue) == 'string':
            rendered = cellValue
            componentCase = 'smartTypeOf(cellValue).string'
            break
        case smartTypeOf(cellValue) == 'number':
            rendered = cellValue
            componentCase = 'smartTypeOf(cellValue).number'
            break
        case smartTypeOf(cellValue) == 'boolean':
            rendered = cellValue
            componentCase = 'smartTypeOf(cellValue).boolean'
            break

        case smartTypeOf(cellValue) == 'object':
            result = new ValueObject4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass
            divClass = result.divClass
            tdStyle = result.tdStyle || {}
            divStyle = result.divStyle || {}
            tdTooltip = result.tdTooltip || ``
            divTooltip = result.divTooltip || ``

            p_2 = false
            componentCase = 'smartTypeOf(cellValue).object'
            break

        case smartTypeOf(cellValue) == 'array':
            const array = cellValue as unknown as TableValueObjectType[]
            const values = array.map((item) => {
                const rendererParams1: TableRendererParams = {
                    controlId,
                    cellValue: item,
                    params,
                    dataLine,
                    column,
                    rowIndex,
                }
                return new ValueObject4(rendererParams1).render()
            })
            // console.log('values', values)
            // rendered = 'aaaaa'
            const { arraySeparator = ' ' } = column
            rendered = values.map((v) => v.rendered).join(arraySeparator)
            tdClass = values[0].tdClass
            p_2 = false
            componentCase = 'smartTypeOf(cellValue).array'
            break

        //============From here there is render base on cellValue================
        case cellValue === null:
            rendered = ''
            componentCase = 'cellValue.null'
            break
        case cellValue === undefined:
            rendered = ''
            componentCase = 'cellValue.undefined'
            break

        default:
            rendered = `Unknown how to render this item: ${cellValue}`
            componentCase = 'default.unknown'
            break
    }

    if (renderer) {
        componentCase = `column.renderer.${renderer}`
        rendered = result.rendered || rendered
        tdClass = result.tdClass || tdClass
        tdStyle = result.tdStyle || tdStyle
        tdTooltip = result.tdTooltip || tdTooltip
        divClass = result.divClass || divClass
        divStyle = result.divStyle || divStyle
        divTooltip = result.divTooltip || divTooltip

        applyPostRenderScript = result.applyPostRenderScript || (() => {})
        applyOnMouseMoveScript = result.applyOnMouseMoveScript || (() => {})
        applyOnChangeScript = result.applyOnChangeScript || (() => {})
        if (column.mode == 'edit') p_2 = false
    }
    // console.log(rendered)
    if (!divStyle['width']) divStyle['width'] = `${column.width || 100}px`
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
    }
}
