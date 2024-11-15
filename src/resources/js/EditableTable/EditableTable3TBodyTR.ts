import { makeTbodyTdEmpty } from './EditableTable3TBodyTDEmpty'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTbodyTr = (params: TableParams) => {
    const { dataSource, columns, tableName } = params
    const firstFixedRightIndex = columns.findIndex((column) => column.fixed === 'right')
    const renderRow = (_: TableDataLine, rowIndex: number) => {
        return columns
            .map((column, columnIndex) => {
                return makeTbodyTdEmpty(
                    tableName,
                    column,
                    rowIndex,
                    columnIndex,
                    firstFixedRightIndex,
                )
            })
            .join('')
    }

    // return 1
    if (!dataSource.data) return ''
    return dataSource.data
        .map((row, rowIndex) => `<tr class="hover:bg-gray-100">${renderRow(row, rowIndex)}</tr>`)
        .join('')
}
