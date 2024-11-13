import { Text4 } from './Controls/EditMode/Text4'
import { ValueObject4 } from './Controls/ViewMode/ValueObject4'
import { smartTypeOf } from './EditableTable3Str'
import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableValueObjectType, TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTCell = (
    params: TableParams,
    row: TableDataLine,
    column: TableColumn,
    rowIndex: number,
) => {
    let cellValue = row[column.dataIndex]
    let rendered: any = ''
    let tdClass: any = ''
    let p_2 = false

    switch (true) {
        case column.renderer == 'no.':
            rendered = rowIndex + 1
            p_2 = true
            break
        case column.renderer == 'text':
            ;[rendered] = new Text4(params, row, column, rowIndex).render()
            p_2 = true
            break

        //============From here there is no renderer================
        case smartTypeOf(cellValue) == 'string':
        case smartTypeOf(cellValue) == 'number':
        case smartTypeOf(cellValue) == 'boolean':
            rendered = cellValue
            p_2 = true
            break
        case smartTypeOf(cellValue) == 'object':
            const result = new ValueObject4(cellValue, params, row, column, rowIndex).render()
            rendered = result.rendered
            tdClass = result.classStr || ''
            break
        case smartTypeOf(cellValue) == 'array':
            const array = cellValue as unknown as TableValueObjectType[]

            const values = array.map((item) =>
                new ValueObject4(item, params, row, column, rowIndex).render(),
            )
            // console.log(values)
            rendered = values.map((v) => v.rendered).join(' ')
            tdClass = values[0].classStr || ''
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
