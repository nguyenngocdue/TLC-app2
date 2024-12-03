import { twMerge } from 'tailwind-merge'
import { getFixedStr } from './FixedColumn/EditableTable3FixedColumn'
import { TableColumn } from './Type/EditableTable3ColumnType'

export const makeEmptyTd = (
    tableName: string,
    column: TableColumn,
    rowIndex: number,
    columnIndex: number,
    firstFixedRightIndex: number,
) => {
    const hiddenStr = column.invisible ? 'hidden' : ''
    const alignStr = column.align ? `text-${column.align}` : ''
    const { dataIndex, renderer } = column
    const fixedStr = getFixedStr(column.fixed, columnIndex, 'td')
    const textStr = `text-sm text-sm-vw`
    const borderL = columnIndex == firstFixedRightIndex ? 'border-l xxx' : ''
    const borderStr = `border-b border-r border-gray-300 ${borderL}`
    const classList = twMerge(`${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${textStr}`)

    const widthStr = column.width ? `width: ${column.width}px;` : ''
    const styleList = `${widthStr}`
    const controlId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`
    return `<td id="${controlId}__td" class="${classList}" style="${styleList}" 
                data-row="${rowIndex}" 
                data-col="${columnIndex}"
                data-column-key="${dataIndex}"
                >
                <div id="${controlId}__div" class="min-h-5 fade-in"></div>
            </td>`
}
