import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3Type'

export const makeTbody = ({ dataSource, columns }: TableParams) => {
    const renderRow = (row: TableDataLine, index: number) => {
        return columns
            .map((column) => {
                const hiddenStr = column.invisible ? 'hidden' : ''
                const alignStr = column.align ? `text-${column.align}` : ''

                const classList = `${hiddenStr} ${alignStr}`

                if (column.dataIndex === '_no_') return `<td class="${classList}">${index + 1}</td>`
                return `<td class="${classList}">${row[column.dataIndex]}</td>`
            })
            .join('')
    }

    return dataSource.map((row, index) => `<tr>${renderRow(row, index)}</tr>`).join('')
}
