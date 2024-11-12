import { Text4 } from './Controls/Text4'
import { ValueObject4 } from './Controls/ValueObject4'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3Type'

export const makeTbody = (params: TableParams) => {
    const { dataSource, columns } = params
    const renderRow = (row: TableDataLine, index: number) => {
        return columns
            .map((column) => {
                const hiddenStr = column.invisible ? 'hidden' : ''
                const alignStr = column.align ? `text-${column.align}` : ''

                const classList = `${hiddenStr} ${alignStr}`

                let value = row[column.dataIndex]
                let rendered, tdClass: any
                switch (true) {
                    case column.renderer == 'no.':
                        rendered = index + 1
                        break
                    case column.renderer == 'text':
                        ;[rendered] = new Text4(params, row, column, index).render()
                        break

                    //============From here there is no renderer================
                    case typeof value == 'string':
                    case typeof value == 'number':
                    case typeof value == 'boolean':
                        rendered = value
                        break
                    case typeof value == 'object':
                        ;[rendered, tdClass] = new ValueObject4(params, row, column, index).render()
                        break
                    default:
                        rendered = 'Unknown how to render this item.'
                        break
                }
                return `<td class="${classList} ${tdClass}">${rendered}</td>`
            })
            .join('')
    }

    // return 1
    return dataSource.data.map((row, index) => `<tr>${renderRow(row, index)}</tr>`).join('')
}
