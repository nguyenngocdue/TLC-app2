import { twMerge } from 'tailwind-merge'
import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTbodyEmpty = (params: TableParams) => {
    const { dataSource, columns, tableName } = params
    const renderRow = (row: TableDataLine, rowIndex: number) => {
        const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
        return columns
            .map((column, columnIndex) => {
                const hiddenStr = column.invisible ? 'hidden' : ''
                const alignStr = column.align ? `text-${column.align}` : ''
                const dataIndex = column.dataIndex
                const fixedStr = getFixedStr(column.fixed, columnIndex, 'td')
                const textStr = `text-sm text-sm-vw`
                const borderL = columnIndex == firstFixedRightIndex ? 'border-l' : ''
                const borderStr = `border-b border-r border-gray-300 ${borderL}`
                const classList = twMerge(
                    `${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${textStr}`,
                )

                const widthStr = column.width ? `width: ${column.width}px;` : ''
                const styleList = `${widthStr}`
                const cellId = `${tableName}__${dataIndex}__${rowIndex}`
                return `<td 
                    id="${cellId}_td"
                    class="${classList}" 
                    style="${styleList}" 
                    ><div id="${cellId}_div" class="min-h-5"></div></td>`
            })
            .join('')
    }

    // return 1
    if (!dataSource.data) return ''
    return dataSource.data
        .map((row, rowIndex) => `<tr class="hover:bg-gray-100">${renderRow(row, rowIndex)}</tr>`)
        .join('')
}
