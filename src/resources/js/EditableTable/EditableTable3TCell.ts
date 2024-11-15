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

export const makeTCell = (
    params: TableParams,
    dataLine: TableDataLine,
    column: TableColumn,
    rowIndex: number,
) => {
    let cellValue = dataLine[column.dataIndex]
    let rendered: any = ''
    let tdClass: any = ''
    let p_2 = true
    let result: TableRenderedValueObject
    let componentCase = ''
    let applyPostScript = () => {}
    // console.log(column.dataIndex, column)

    const { tableName } = params
    const { dataIndex } = column
    const controlName = `${tableName}[${dataIndex}][${rowIndex}]`
    const controlId = `${tableName}__${dataIndex}__${rowIndex}`

    const rendererParams = {
        controlName,
        controlId,
        cellValue,
        params,
        dataLine,
        column,
        rowIndex,
    }

    switch (true) {
        case column.renderer == 'no.':
            rendered = rowIndex + 1
            componentCase = 'column.renderer.no.'
            break

        case column.renderer == 'text':
        case column.renderer == 'text4': // this line will be removed for new flexible MODE
            result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.text'
            break

        case column.renderer == 'number':
        case column.renderer == 'number4': // this line will be removed for new flexible MODE
            result = new Number4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.number'
            break

        case column.renderer == 'dropdown':
            result = new Dropdown4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.dropdown'
            break

        case column.renderer == 'toggle':
            result = new Toggle4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.toggle'
            break

        case column.renderer == 'checkbox':
            result = new Checkbox4(rendererParams).render()
            // result = new Text4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr
            applyPostScript = result.applyPostScript || (() => {})
            componentCase = 'column.renderer.checkbox'
            break

        case column.renderer == 'picker_datetime':
            result = new PickerDateTime4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr
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
            tdClass = result.classStr
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
            tdClass = values[0].classStr
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
    return { rendered, tdClass, p_2, componentCase, applyPostScript }
}
