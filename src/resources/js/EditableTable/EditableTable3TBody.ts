import { Text4 } from './Controls/Text4'
import { ValueObject4 } from './Controls/ValueObject4'
import { smartTypeOf } from './EditableTable3Str'
import { TableValueObjectType, TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3Type'

export const makeTbody = (params: TableParams) => {
    const { dataSource, columns } = params
    const renderRow = (row: TableDataLine, index: number) => {
        return columns
            .map((column) => {
                const hiddenStr = column.invisible ? 'hidden' : ''
                const alignStr = column.align ? `text-${column.align}` : ''

                let cellValue = row[column.dataIndex]
                let rendered, tdClass: any
                switch (true) {
                    case column.renderer == 'no.':
                        rendered = index + 1
                        break
                    case column.renderer == 'text':
                        ;[rendered] = new Text4(params, row, column, index).render()
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
                            index,
                        ).render()
                        break
                    case smartTypeOf(cellValue) == 'array':
                        const array = cellValue as unknown as TableValueObjectType[]

                        const values = array.map((item) =>
                            new ValueObject4(item, params, row, column, index).render(),
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
                const classList = `${hiddenStr} ${alignStr} ${tdClass} border-b border-r border-gray-300`

                const widthStr = column.width ? `width: ${column.width}px;` : ''
                const styleList = `${widthStr}`
                return `<td class="${classList}" style="${styleList}">${rendered}</td>`
            })
            .join('')
    }

    // return 1
    return dataSource.data.map((row, index) => `<tr>${renderRow(row, index)}</tr>`).join('')
}
