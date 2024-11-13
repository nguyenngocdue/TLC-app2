import { ValueObject4 } from './Renderer/ValueObject/ValueObject4'
import { smartTypeOf } from './EditableTable3Str'
import { TableColumn } from './Type/EditableTable3ColumnType'
import {
    TableValueObjectType,
    TableDataLine,
    TableRenderedValueObject,
} from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

import { Text4 } from './Renderer/Text/Text4'
import { Dropdown4 } from './Renderer/Dropdown/Dropdown4'
import { Toggle4 } from './Renderer/Toggle/Toggle4'
import { Number4 } from './Renderer/Number/Number4'
import { Checkbox4 } from './Renderer/Checkbox/Checkbox4'

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
    const rendererParams = { cellValue, params, dataLine, column, rowIndex }

    switch (true) {
        case column.renderer == 'no.':
            rendered = rowIndex + 1
            break

        case column.renderer == 'text':
            result = new Text4(rendererParams).render()
            rendered = result.rendered
            break

        case column.renderer == 'number':
            result = new Number4(rendererParams).render()
            rendered = result.rendered
            break

        case column.renderer == 'dropdown':
            result = new Dropdown4(rendererParams).render()
            rendered = result.rendered
            break

        case column.renderer == 'toggle':
            result = new Toggle4(rendererParams).render()
            rendered = result.rendered
            break

        case column.renderer == 'checkbox':
            result = new Checkbox4(rendererParams).render()
            rendered = result.rendered
            break

        case column.renderer == 'picker_datetime':
            break

        //============From here there is no renderer================
        case smartTypeOf(cellValue) == 'string':
        case smartTypeOf(cellValue) == 'number':
        case smartTypeOf(cellValue) == 'boolean':
            rendered = cellValue
            break

        case smartTypeOf(cellValue) == 'object':
            result = new ValueObject4(rendererParams).render()
            rendered = result.rendered
            tdClass = result.classStr || ''
            p_2 = false
            break

        case smartTypeOf(cellValue) == 'array':
            const array = cellValue as unknown as TableValueObjectType[]
            const values = array.map((item) => {
                const rendererParams1 = { cellValue: item, params, dataLine, column, rowIndex }
                return new ValueObject4(rendererParams1).render()
            })
            // console.log('values', values)
            // rendered = 'aaaaa'
            rendered = values.map((v) => v.rendered).join(' ')
            tdClass = values[0].classStr || ''
            p_2 = false
            break

        //============From here there is render base on cellValue================
        case cellValue === null:
        case cellValue === undefined:
            rendered = ''
            break

        default:
            rendered = `Unknown how to render this item: ${cellValue}`
            break
    }
    // console.log(rendered)
    return { rendered, tdClass, p_2 }
}
