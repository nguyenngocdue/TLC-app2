import { ValueObject4 } from './Renderer/ValueObject/ValueObject4'
import { smartTypeOf } from './Function/Functions'
import { TableColumn } from './Type/EditableTable3ColumnType'
import {
    TableValueObjectType,
    TableDataLine,
    TableRenderedValueObject,
    TableRendererParams,
} from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

import { Text4 } from './Renderer/Text/Text4'
import { Dropdown4 } from './Renderer/Dropdown/Dropdown4'
import { Toggle4 } from './Renderer/Toggle/Toggle4'
import { Number4 } from './Renderer/Number/Number4'
import { Checkbox4 } from './Renderer/Checkbox/Checkbox4'
import { PickerDateTime4 } from './Renderer/PickerDateTime/PickerDateTime4'
import { CustomFunction4 } from './Renderer/CustomFunction/CustomFunction4'
import { IdLink } from './Renderer/Id/IdLink'

export const makeTCell = (
    params: TableParams,
    dataLine: TableDataLine,
    column: TableColumn,
    rowIndex: number,
) => {
    let cellValue = dataLine[column.dataIndex]
    let rendered: any = ''
    let tdClass: string | undefined = ''
    let divClass: string | undefined = ''
    let tdStyle: { [key: string]: string | number } | undefined = {}
    let divStyle: { [key: string]: string | number } | undefined = {}
    let p_2 = true
    let result: TableRenderedValueObject
    let componentCase = 'not-yet-defined'
    let applyPostScript = () => {}
    // console.log(column.dataIndex, column)

    const { tableName } = params
    const { dataIndex, renderer } = column
    const controlName = `${tableName}[${dataIndex}][${rowIndex}]`
    const controlId = `${tableName}__${dataIndex}__${rendered}__${rowIndex}`

    const rendererParams: TableRendererParams = {
        controlName,
        controlId,
        cellValue,
        params,
        dataLine,
        column,
        rowIndex,
    }

    switch (true) {
        case column.renderer == 'parent_link':
        case column.renderer == 'thumbnail':
        case column.renderer == 'thumbnails':
        case column.renderer == 'status':
        case column.renderer == 'avatar_user':
        case column.renderer == 'agg_count':
        case column.renderer == 'id_status_link':
        case column.renderer == 'id_status':
        case column.renderer == 'column':
        case column.renderer == 'column_link':
        case column.renderer == 'hyper-link':
        case column.renderer == 'doc-id':
        case column.renderer == 'qr-code':
        case column.renderer == 'action_checkbox.':
        case column.renderer == 'action_print.':
            rendered = `${column.renderer} to be implemented`
            tdClass = 'whitespace-nowrap'
            componentCase = `column.renderer.${column.renderer}`
            break
        case column.renderer == 'action.':
        case column.renderer == 'custom_function':
            rendererParams.customRenderFn = () => {
                return {
                    rendered: `Hello ${cellValue}`,
                    tdClass: '',
                    divClass: '',
                    tdStyle: {},
                    divStyle: {},
                    applyPostScript: () => {},
                }
            }
            result = new CustomFunction4(rendererParams).render()
            rendered = result.rendered
            componentCase = 'column.renderer.custom_function'
            break
        case column.renderer == 'no.':
            rendered = rowIndex + 1
            componentCase = 'column.renderer.no.'
            break
        case column.renderer == 'id_link':
            result = new IdLink(rendererParams).render()
            tdClass = result.tdClass
            rendered = result.rendered
            componentCase = 'column.renderer.id_link'
            break

        case column.renderer == 'text':
        case column.renderer == 'text4': // this line will be removed for new flexible MODE
            result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass
            divClass = result.divClass
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.text'
            break

        case column.renderer == 'number':
        case column.renderer == 'number4': // this line will be removed for new flexible MODE
            result = new Number4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.number'
            break

        case column.renderer == 'dropdown':
        case column.renderer == 'dropdown4': // this line will be removed for new flexible MODE
            result = new Dropdown4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.dropdown'
            break

        case column.renderer == 'toggle':
        case column.renderer == 'toggle4': // this line will be removed for new flexible MODE
            result = new Toggle4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass

            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.toggle'
            break

        case column.renderer == 'checkbox':
        case column.renderer == 'checkbox4': // this line will be removed for new flexible MODE
            result = new Checkbox4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.checkbox'
            break

        case column.renderer == 'picker_datetime':
        case column.renderer == 'date-time':
            result = new PickerDateTime4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.tdClass
            divStyle = result.divStyle || {}
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.picker_datetime'
            break

        case column.renderer != undefined:
            rendered = `Unknown renderer: ${column.renderer}`
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
            p_2 = false
            componentCase = 'smartTypeOf(cellValue).object'
            break

        case smartTypeOf(cellValue) == 'array':
            const array = cellValue as unknown as TableValueObjectType[]
            const values = array.map((item) => {
                const rendererParams1: TableRendererParams = {
                    controlName,
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
        case cellValue === undefined:
            rendered = ''
            componentCase = 'cellValue.null_or_undefined'
            break

        default:
            rendered = `Unknown how to render this item: ${cellValue}`
            componentCase = 'default.unknown'
            break
    }

    if (column.renderer && column.mode == 'edit') p_2 = false
    // console.log(rendered)
    if (!divStyle['width']) divStyle['width'] = `${column.width || 100}px`
    return { rendered, tdClass, divClass, tdStyle, divStyle, p_2, componentCase, applyPostScript }
}
