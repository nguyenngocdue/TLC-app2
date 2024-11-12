import { Text4 } from './Controls/Text4'
import { ValueObject4 } from './Controls/ValueObject4'
import { getFirstFixedRightColumnIndex } from './EditableTable3FixedColumn'
import { smartTypeOf } from './EditableTable3Str'
import { TableValueObjectType, TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTbody = (params: TableParams) => {
    const { dataSource, columns } = params
    const renderRow = (row: TableDataLine, rowIndex: number) => {
        const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
        return columns
            .map((column, columnIndex) => {
                const hiddenStr = column.invisible ? 'hidden' : ''
                const alignStr = column.align ? `text-${column.align}` : ''

                let cellValue = row[column.dataIndex]
                let rendered: any = ''
                let tdClass: any = ''
                switch (true) {
                    case column.renderer == 'no.':
                        rendered = rowIndex + 1
                        break
                    case column.renderer == 'text':
                        ;[rendered] = new Text4(params, row, column, rowIndex).render()
                        break

                    //============From here there is no renderer================
                    case smartTypeOf(cellValue) == 'string':
                    case smartTypeOf(cellValue) == 'number':
                    case smartTypeOf(cellValue) == 'boolean':
                        rendered = cellValue
                        break
                    case smartTypeOf(cellValue) == 'object':
                        ;[rendered, tdClass] = new ValueObject4(
                            cellValue,
                            params,
                            row,
                            column,
                            rowIndex,
                        ).render()
                        break
                    case smartTypeOf(cellValue) == 'array':
                        const array = cellValue as unknown as TableValueObjectType[]

                        const values = array.map((item) =>
                            new ValueObject4(item, params, row, column, rowIndex).render(),
                        )
                        console.log(values)
                        rendered = values.map((v) => v[0]).join(' ')
                        tdClass = values[0][1]
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

                const fixed = column.fixed
                    ? `table-td-fixed-${column.fixed} table-td-fixed-${column.fixed}-${columnIndex}`
                    : ''

                const borderL = columnIndex == firstFixedRightIndex ? 'border-l' : ''
                const borderStr = `border-b border-r border-gray-300 ${borderL}`
                const classList = `${hiddenStr} ${alignStr} ${tdClass} ${fixed} ${borderStr}`

                const widthStr = column.width ? `width: ${column.width}px;` : ''
                const styleList = `${widthStr}`
                return `<td class="${classList}" style="${styleList}">${rendered}</td>`
            })
            .join('')
    }

    // return 1
    return dataSource.data.map((row, rowIndex) => `<tr>${renderRow(row, rowIndex)}</tr>`).join('')
}
