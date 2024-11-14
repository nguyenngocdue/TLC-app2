import { twMerge } from 'tailwind-merge'
import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { makeTCell } from './EditableTable3TCell'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTbody = (params: TableParams) => {
    const { dataSource, columns } = params
    const renderRow = (row: TableDataLine, rowIndex: number) => {
        const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
        return columns
            .map((column, columnIndex) => {
                const hiddenStr = column.invisible ? 'hidden' : ''
                const alignStr = column.align ? `text-${column.align}` : ''

                const tCell = makeTCell(params, row, column, rowIndex)

                const { rendered, tdClass, p_2, componentCase } = tCell

                const p = p_2 ? 'p-2' : ''
                const fixedStr = getFixedStr(column.fixed, columnIndex, 'td')
                const textStr = `text-sm text-sm-vw 1text-gray-700`
                const borderL = columnIndex == firstFixedRightIndex ? 'border-l' : ''
                const borderStr = `border-b border-r border-gray-300 ${borderL}`
                const classList = twMerge(
                    `${hiddenStr} ${alignStr} ${tdClass} ${fixedStr} ${borderStr} ${textStr} ${p}`,
                )

                const widthStr = column.width ? `width: ${column.width}px;` : ''
                const styleList = `${widthStr}`
                return `<td class="${classList}" style="${styleList}" componentCase="${componentCase}">${rendered}</td>`
            })
            .join('')
    }

    // return 1
    if (!dataSource.data) return ''
    return dataSource.data
        .map((row, rowIndex) => `<tr class="hover:bg-gray-100">${renderRow(row, rowIndex)}</tr>`)
        .join('')
}
